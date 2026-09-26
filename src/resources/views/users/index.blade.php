@extends('me::master')

@php $prefix = request()->segment(1); @endphp

@section('title', trans('me::me.Users'))

@push('buttons')
  @component('me::components.btn.add-button', [
      'route' => route("{$prefix}.users.create"),
      'text' => __('me::me.Add User'),
      'class' => 'btn-encodex-create'
  ])
  @endcomponent
@endpush

@section('content')
<div class="card glass-card w-100">
    <form method="GET" action="{{ url()->current() }}" class="mb-3">
        <div class="row g-2">
            <div class="col-md">
                <input type="text" name="name" class="form-control form-control-sm" placeholder="@lang('me::me.Name')" value="{{ request('name') }}">
            </div>
            <div class="col-md">
                <input type="text" name="email" class="form-control form-control-sm" placeholder="@lang('me::me.Email')" value="{{ request('email') }}">
            </div>
            <div class="col-md">
                <select name="role" class="form-select form-select-sm">
                    <option value="">@lang('me::me.All Roles')</option>
                    @foreach ($roles ?? [] as $role)
                        <option value="{{ $role->id }}" {{ (string) request('role') === (string) $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-sm btn-encodex-search rounded">
                    <i class="fas fa-search"></i> @lang('me::me.Search')
                </button>
                <a href="{{ url()->current() }}" class="btn btn-sm btn-encodex-clear rounded">
                    <i class="fas fa-eraser"></i> @lang('me::me.Reset')
                </a>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-sm table-bordered table-hover table-striped table-encodex table-sm">
            <thead class="text-center">
                <tr>
                    <th>#</th>
                    <th>@lang('me::me.Avatar')</th>
                    <th>@lang('me::me.Name')</th>
                    <th>@lang('me::me.Email')</th>
                    <th>@lang('me::me.Roles')</th>
                    <th>@lang('me::me.Status')</th>
                    <th>@lang('me::me.Created At')</th>
                    <th>@lang('me::me.Actions')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ toBanglaNumber($loop->iteration) }}</td>
                        <td class="text-center">
                            @if($user->profile_image)
                                <img src="{{ route('profile_img.show', ($user->profile_image)) }}"
                                        alt="{{ $user->name }}" class="rounded-circle"
                                        style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px; margin: 0 auto;">
                                    <i class="fas fa-user text-secondary"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge bg-info">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success">@lang('me::me.Active')</span>
                            @else
                                <span class="badge bg-danger">@lang('me::me.Inactive')</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at ? $user->created_at->format('d M Y') : __('me::me.N/A') }}</td>
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center gap-1">
                                <a href="{{ route("{$prefix}.users.show", $user->id) }}" class="btn btn-sm me-1 btn-encodex-show" title="@lang("me::me.View")">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route("{$prefix}.users.edit", $user->id) }}" class="btn btn-sm me-1 btn-encodex-edit {{ $user->id === auth()->id() ? 'disabled-link' : '' }}" title="@lang("me::me.Edit")" >
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route("{$prefix}.users.toggle-active", $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm me-1 {{ $user->is_active ? 'btn-encodex-deactive' : 'btn-encodex-active' }}"
                                        title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}"
                                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                        <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                    </button>
                                </form>

                                <form action="{{ route("{$prefix}.users.destroy", $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-encodex-delete" title="@lang("me::me.Delete")"
                                        onclick="return confirm('{{ __('me::me.Are you sure you want to delete this?') }}')"
                                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">@lang('me::me.No users found')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div class="mt-3">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection

@push('css')
    <style>
        .disabled-link {
            pointer-events: none; /* click নিষ্ক্রিয় করবে */
            color: gray;          /* visual disable effect */
            text-decoration: none;
        }
    </style>

@endpush
