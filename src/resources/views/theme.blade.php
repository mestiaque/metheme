@extends('me::master')

@section('title', trans('Clear Data'))

@section('content')

<div class="card glass-card shadow-lg">
    <div class="card-header d-flex align-items-center">
        <h5 class="mb-0">@lang('Sidebar Color Plate')</h5>
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
        <h5 class="mb-0">@lang('Buttons')</h5>
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
                    'btn-encodex-save',
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

@endsection

@push('css')

@endpush
