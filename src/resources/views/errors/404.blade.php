@extends('me::blank')
@section('title', __('me::me.404 Not Found'))
@section('meta-title', __('me::me.404 Not Found'))
@section('content')
    <div class="mb-4 animate__animated animate__bounceIn">
        <i class="fas fa-ghost encodex-icon"></i>
    </div>

    <!-- 404 Code -->
    <div class="error-code animate__animated animate__fadeInDown">404</div>

    <!-- Error message -->
    <p class="error-message animate__animated animate__fadeInUp mb-4">
        @lang('me::me.Oops! The page you are looking for does not exist.')
    </p>

    <a href="{{ url('/') }}" class="btn btn-blank mt-4">@lang('me::me.Go to Homepage')</a>
@endsection