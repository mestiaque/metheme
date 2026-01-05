@extends('me::blank')
@section('title', 'Login')
@section('meta-title', 'Login - ' . config('app.name'))
@section('content')
    <div class="login-card">
        <div class="login-form">
            <div class="login-header">
                <div class="login-avatar">
                    <img loading="lazy" src="{{ asset('assets/img/favicon/Encodex.ico') }}" class="brand-image opacity-75 shadow" style="width: 100%" alt="mESTIAQUE">
                </div>
                <h1 class="login-title text-shadow">{{ __('FORGOT PASSWORD') }}</h1>
                <p style="text-align: center; color:#ffffff7a" class="text-shadow">
                    {{ __('No problem. Just let us know your email address and we will email you a password reset link.') }}
                </p>
            </div>

            <!-- Session Status (সফলভাবে ইমেইল গেলে দেখাবে) -->
            @if (session('status'))
                <div class="alert alert-success mb-4" style="background: rgba(40, 167, 69, 0.2); color: #2ecc71; border: 1px solid #2ecc71;">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="#">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label class="text-shadow" for="email">{{ __('EMAIL ADDRESS') }}</label>
                    <input type="email" class="form-control text-shadow box-shadow" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <button type="submit" class="btn-login btn btn-blank box-shadow mt-3">
                    {{ __('EMAIL PASSWORD RESET LINK') }}
                </button>

                <div class="text-center mt-4">
                    <p style="color:#ffffff7a" class="text-shadow">
                        {{ __('Remember your password?') }}
                        <a href="{{ route('login') }}" style="color: #fff; text-decoration: underline;">{{ __('Back to Login') }}</a>
                    </p>
                </div>
            </form>
        </div>
    </div>


    @include("me::auth.css")

@endsection
