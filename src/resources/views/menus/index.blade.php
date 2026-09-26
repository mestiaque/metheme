@extends('me::master')

@section('title', trans('me::me.Menu Management'))

@push('buttons')
<button class="btn btn-sm btn-encodex-create" data-bs-toggle="modal" data-bs-target="#createMenuModal">
    <i class="fas fa-plus"></i> {{ __('me::me.Add Menu') }}
</button>
@endpush

@section('content')
<div class="container-fluids">
    <div class="card shadow mb-4 w-100">
        <div class="card-body">
            <form method="GET" action="{{ route('me.menus.index') }}" class="mb-3 glass-search-form">
                <div class="row">
                    <div class="col-md">
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="@lang('me::me.Enter Name')" value="{{ request('name') }}">
                    </div>
                    <div class="col-md">
                        <input type="text" name="url" class="form-control form-control-sm" placeholder="@lang('me::me.Enter URL')" value="{{ request('url') }}">
                    </div>
                    <div class="col-md">
                        <input type="text" name="type" class="form-control form-control-sm" placeholder="@lang('me::me.Enter Type')" value="{{ request('type') }}">
                    </div>
                    <div class="col-md">
                        <button type="submit" class="btn btn-sm btn-encodex-search rounded">
                            <i class="fas fa-search"></i> @lang('me::me.Search')
                        </button>
                        <a href="{{ route('me.menus.index') }}" class="btn btn-sm btn-encodex-clear rounded">
                            <i class="fas fa-eraser"></i> @lang('me::me.Reset')
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover table-striped table-encodex text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('me::me.Name')</th>
                        <th>@lang('me::me.Icon')</th>
                        <th>@lang('me::me.URL')</th>
                        <th>@lang('me::me.Type')</th>
                        <th>@lang('me::me.Order')</th>
                        <th>@lang('me::me.Status')</th>
                        <th>@lang('me::me.Actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($menus as $menu)
                        <tr>
                            <td>{{ toBanglaNumber($loop->iteration) }}</td>
                            <td>{{ $menu->name }}</td>
                            <td>{{ $menu->icon }}</td>
                            <td>{{ $menu->url }}</td>
                            <td>{{ $menu->type }}</td>
                            <td>{{ $menu->order }}</td>
                            <td>
                                @if($menu->is_active)
                                    <span class="badge badge-encodex text-success">{{ __('me::me.ON') }}</span>
                                @else
                                    <span class="badge badge-encodex text-danger">{{ __('me::me.OFF') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-inline-flex align-items-center gap-1">
                                    <button class="btn btn-sm btn-encodex-edit" data-bs-toggle="modal" data-bs-target="#editMenuModal{{ $menu->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('me.menus.destroy', $menu->id) }}" method="POST" class="d-inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-encodex-delete"
                                                onclick="return confirm('{{ __('me::me.Are you sure you want to delete this?') }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">@lang('me::me.No menu found')</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $menus->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createMenuModal" tabindex="-1">
    <div class="modal-dialog glass-card modal-lg">
        <form action="{{ route('me.menus.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('me::me.Add Menu') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @include('me::menus.partials.form', ['menu' => null])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-encodex-cancel" data-bs-dismiss="modal">{{ __('me::me.Close') }}</button>
                <button type="submit" class="btn btn-sm btn-encodex-save">{{ __('me::me.Save') }}</button>
            </div>
        </form>
    </div>
</div>

@foreach($menus as $menu)
    <div class="modal fade" id="editMenuModal{{ $menu->id }}" tabindex="-1">
        <div class="modal-dialog glass-card modal-lg">
            <form action="{{ route('me.menus.update', $menu->id) }}" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('me::me.Edit Menu') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('me::menus.partials.form', ['menu' => $menu])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-encodex-cancel" data-bs-dismiss="modal">{{ __('me::me.Close') }}</button>
                    <button type="submit" class="btn btn-sm btn-encodex-save">{{ __('me::me.Update') }}</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

@endsection
