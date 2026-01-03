@extends('me::blank')
@section('title', 'Login')
@section('meta-title', 'Login - ' . config('app.name'))
@section('content')
    <div class="login-card">
        <div class="login-form">
            <div class="login-header">
                <div class="login-avatar">
                    {{-- <i class="fas fa-user"></i> --}}
                    <img loading="lazy" src="{{ asset('assets/img/favicon/Encodex.ico') }}" class="brand-image opacity-75 shadow " style="width: 100%" alt="mESTIAQUE">
                </div>
                <h1 class="login-title text-shadow">{{ __('WELCOME') }}</h1>
                <p style="text-align: center; color:#ffffff7a" class="text-shadow">{{ __('Enter your username and password to log in.') }}</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success mb-4">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group ">
                    <label class="text-shadow" for="email">{{ __('EMAIL') }}</label>
                    <input type="email" class="form-control text-shadow box-shadow" id="email" name="email" placeholder="{{ __('EMAIL') }}"
                        value="{{ old('email') }}" spellcheck="false" required autofocus>
                </div>

                <div class="form-group" style="position: relative;">
                    <label class="text-shadow" for="password">{{ __('PASSWORD') }}</label>
                    <input type="password" class="form-control text-shadow box-shadow" id="password" name="password" placeholder="{{ __('PASSWORD') }}" required>
                    <span class="password-toggle text-shadow" id="togglePassword" style="position: absolute; right: 15px; top: 41px; cursor: pointer;">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>


                <div class="remember-me mb-3">
                    <label class="custom-checkbox text-shadow ">
                        <input type="checkbox" class="box-shadow" id="remember_me" name="remember">
                        <span class="box-shadow"></span>
                        {{ __('Remember Me') }}
                    </label>
                </div>

                <div>
                    <button type="submit" class="btn-login btn btn-blank box-shadow px-3">
                        {{ __('LOGIN') }}
                    </button>
                </div>
            </form>
        </div>
    </div>


    @include("me::auth.login-css")
    @push('css')
    <style>
        .btn-login{
            padding-left: 2rem !important;
            padding-right: 2rem !important;
        }
    </style>
    @endpush

    @push('js')
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.querySelector('#togglePassword');
            const passwordInput = document.querySelector('#password');

            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle the icon class
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
    </script>
    @endpush
@endsection
