@extends('me::blank')
@section('title', __('me::me.500 Server Error'))
@section('meta-title', __('me::me.500 Server Error'))
@section('content')
    <div class="mb-4 animate__animated animate__bounceIn">
        <i class="fas fa-tools encodex-icon"></i>
    </div>
    <div class="error-code animate__animated animate__fadeInDown">500</div>
    <p class="error-message animate__animated animate__fadeInUp mb-4">
        @lang('me::me.Something went wrong on our end. We are fixing it!')
    </p>
    <button type="button" onclick="window.location.reload()" class="btn btn-blank mt-4">@lang('me::me.Try Again')</button>
@endsection
