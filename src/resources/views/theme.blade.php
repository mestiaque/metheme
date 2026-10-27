@extends('me::master')

@section('title', trans('me::me.Clear Data'))

@section('content')

<div class="card glass-card shadow-lg">
    <div class="card-header d-flex align-items-center">
        <h5 class="mb-0">@lang('me::me.Sidebar Color Plate')</h5>
    </div>

    <div class="card-body">
        <div class="row">
            @for($i = 1; $i <= 100; $i++)
                <div class="col-md-1 mb-3">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center ">
                            <div class="color-plate {{ 'icc-' . $i }}"> <i class="fa fa-stop fa-3x"></i></div>
                            <p class="mb-0">{{ 'icc-' . $i }}</p>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

    </div>
</div>

<div class="card glass-card shadow-lg">
    <div class="card-header d-flex align-items-center">
        <h5 class="mb-0">@lang('me::me.Buttons')</h5>
    </div>

    <div class="card-body">
        <div class="row">
            @php
                $buttonClasses = [
                    'btn-encodex-list',
                    'btn-encodex-search',
                    'btn-encodex-create',
                    'btn-encodex-cancel',
                    'btn-encodex-print',
                    'btn-encodex-edit',
                    'btn-encodex-payment',
                    'btn-encodex-show',
                    'btn-encodex-clear',
                    'btn-encodex-active',
                    'btn-encodex-deactive',
                    'btn-encodex-delete',
                    'btn-encodex-pink',
                    'btn-encodex',
                    'btn-encodex-save',
                    'btn-encodex-print2',
                    'btn-encodex-download',
                    'btn-encodex-excel',
                    'btn-encodex-money',
                    'btn-encodex-blank',
                    'btn-savex',
                    'btn-close',
                ];
            @endphp
            @foreach($buttonClasses as $class)
                <div class="col-md-2 mb-3">
                    <button class="btn {{ $class }} w-100">{{ $class }}</button>
                </div>
            @endforeach
        </div>

    </div>
</div>

<div class="card glass-card shadow-lg">
    <div class="card-header d-flex align-items-center">
        <h5 class="mb-0">@lang('me::me.Colors')</h5>
    </div>

    <div class="card-body">
        <div class="row">
            @foreach(['bg-encodex', 'bg-encodex-secondary', 'bg-encodex-light'] as $class)
                <div class="col-md-2 mb-3">
                    <div class="{{ $class }} text-white rounded p-3 text-center">{{ $class }}</div>
                </div>
            @endforeach
            <div class="col-md-2 mb-3">
                <div class="text-encodex fw-bold border rounded p-3 text-center">text-encodex</div>
            </div>
        </div>
    </div>
</div>

<div class="card glass-card shadow-lg">
    <div class="card-header d-flex align-items-center">
        <h5 class="mb-0">@lang('me::me.Badges')</h5>
    </div>

    <div class="card-body">
        <span class="badge badge-encodex">badge-encodex</span>
    </div>
</div>

<div class="card glass-card shadow-lg">
    <div class="card-header d-flex align-items-center">
        <h5 class="mb-0">@lang('me::me.Shadows')</h5>
    </div>

    <div class="card-body">
        <div class="row">
            @for($i = 1; $i <= 40; $i++)
                <div class="col-md-2 mb-4">
                    <div class="{{ 'shadow-x' . $i }} bg-white rounded p-3 text-center">{{ 'shadow-x' . $i }}</div>
                </div>
            @endfor
        </div>
    </div>
</div>

<div class="card glass-card shadow-lg">
    <div class="card-header d-flex align-items-center">
        <h5 class="mb-0">@lang('me::me.Tables')</h5>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-encodex">
            <thead>
                <tr><th>table-encodex</th><th>Column</th><th>Column</th></tr>
            </thead>
            <tbody>
                <tr><td>Row 1</td><td>Value</td><td>Value</td></tr>
                <tr><td>Row 2</td><td>Value</td><td>Value</td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card glass-card shadow-lg">
    <div class="card-header d-flex align-items-center">
        <h5 class="mb-0">@lang('me::me.Cards')</h5>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card glass-card h-100"><div class="card-body text-center">glass-card</div></div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="blur-card rounded p-3 h-100 text-center">blur-card</div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card custom-card h-100"><div class="card-body text-center"><i class="fas fa-star"></i> custom-card</div></div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="glass-bar bg-encodex text-white rounded p-3 h-100 text-center">glass-bar</div>
            </div>
        </div>
    </div>
</div>

<div class="card glass-card shadow-lg">
    <div class="card-header d-flex align-items-center">
        <h5 class="mb-0">@lang('me::me.Icons')</h5>
    </div>

    <div class="card-body">
        <div class="row">
            @foreach(['icon-products', 'icon-sales', 'icon-purchases', 'icon-stock', 'icon-product-variants', 'icon-brands', 'icon-packs', 'icon-settings'] as $class)
                <div class="col-md-2 mb-3 text-center">
                    <a href="#" class="btn btn-quick-access w-100">
                        <span class="quick-access-icon {{ $class }} p-3"><i class="fas fa-cube fa-2x"></i></span>
                        <p class="mb-0 mt-2">{{ $class }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@push('css')

@endpush
