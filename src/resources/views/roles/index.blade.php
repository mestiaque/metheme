
@extends('me::master')

@section('title', trans('me::me.Roles'))

@push('buttons')
  @component('me::components.btn.add-button', [
      'route' => route('roles.create'),
      'text' => __('me::me.Add Role'),
      'class' => 'btn-encodex-create'
  ])
  @endcomponent
@endpush

@section('content')
    <div class="card glass-card mb-4 w-100">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped table-encodex table-sm">
                <thead class="text-center">
                    <tr>
                        <th>#</th>
                        <th>@lang('me::me.Role Name')</th>
                        <th>@lang('me::me.Parent Role')</th>
                        <th>@lang('me::me.Slug')</th>
                        <th>@lang('me::me.Description')</th>
                        <th>@lang('me::me.Users')</th>
                        <th>@lang('me::me.Actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>{{ toBanglaNumber($loop->iteration) }}</td>
                            <td>@include('me::roles.partials.badge', ['role' => $role])</td>
                            <td>
                                @if($role->parent)
                                    @include('me::roles.partials.badge', ['role' => $role->parent])
                                @else
                                    <span class="badge bg-dark text-white">@lang('me::me.Top role')</span>
                                @endif
                            </td>
                            <td><code>{{ $role->slug }}</code></td>
                            <td>{{ $role->description ?? __('me::me.N/A') }}</td>
                            <td>
                                <span class="badge bg-info">{{ $role->users_count }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <a href="{{ route("roles.show", $role->id) }}" class="btn btn-sm btn-encodex-show me-1" title="@lang("me::me.View")">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(!in_array($role->slug, \ME\Models\Roles::SUPER_ADMIN_SLUGS, true) && in_array($role->id, $manageable ?? []))
                                    <a href="{{ route("roles.edit", $role->id) }}" class="btn btn-sm btn-encodex-edit me-1" title="@lang("me::me.Edit")">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @php
                                        // Only roles below yours can be deleted, and only when they have no child roles
                                        $cannotDelete = $role->children_count > 0;
                                    @endphp
                                    <form action="{{ route("roles.destroy", $role->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-encodex-delete" title="@lang("me::me.Delete")"
                                            onclick="return confirm('{{ __('me::me.Are you sure you want to delete this?') }}')"
                                            {{ $cannotDelete ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">@lang('me::me.No roles found')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($roles->hasPages())
            <div class="mt-3">
                {{ $roles->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
