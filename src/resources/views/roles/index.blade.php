@php $prefix = request()->segment(1); @endphp

@extends('me::master')

@section('title', trans('me::me.Roles'))

@push('buttons')
  @component('me::components.btn.add-button', [
      'route' => route('me.roles.create'),
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
                            <td>{{ $role->name }}</td>
                            <td><code>{{ $role->slug }}</code></td>
                            <td>{{ $role->description ?? __('me::me.N/A') }}</td>
                            <td>
                                <span class="badge bg-info">{{ $role->users_count }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <a href="{{ route("{$prefix}.roles.show", $role->id) }}" class="btn btn-sm btn-encodex-show me-1" title="@lang("me::me.View")">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($role->slug !== 'encodex')
                                    <a href="{{ route("{$prefix}.roles.edit", $role->id) }}" class="btn btn-sm btn-encodex-edit me-1" title="@lang("me::me.Edit")">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @php
                                        // Super admin and the logged-in user's own roles can't be deleted
                                        $cannotDelete = in_array($role->slug, ['super-admin', 'super_admin'], true)
                                            || auth()->user()->roles->contains('id', $role->id);
                                    @endphp
                                    <form action="{{ route("{$prefix}.roles.destroy", $role->id) }}" method="POST" class="d-inline">
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
                            <td colspan="6" class="text-center">@lang('me::me.No roles found')</td>
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
