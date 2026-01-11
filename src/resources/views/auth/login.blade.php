@extends('me::blank')
@section('title', 'Login')
@section('meta-title', 'Login - ' . config('app.name'))
@section('content')
    <div class="login-card glass-card">
        <div class="login-form">
            <div class="login-header mb-0">
                <div class="login-avatar">
                    {{-- <i class="fas fa-user"></i> --}}
                    <img loading="lazy" src="{{ asset('assets/img/favicon/Encodex.ico') }}" class="brand-image opacity-75 shadow " style="width: 100%" alt="mESTIAQUE">
                </div>
                <h1 class="login-title text-shadow">{{ __('WELCOME') }}</h1>
                <p style="text-align: center; color:#ffffff7a" class="text-shadow">{{ __('Enter your email/phone and password to log in.') }}</p>
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
                <!-- Tab Labels (Clickable) -->
                <div class="form-group" style="position: relative;">
                    <label class="text-shadow" for="user_name">{{ __('EMAIL / PHONE') }}</label>
                    <input type="text" spellcheck="false" class="form-control text-shadow box-shadow @error('user_name') is-invalid @enderror" name="user_name" id="email" placeholder="{{ __('EMAIL OR PHONE') }}" value="{{ old('user_name') }}" required>
                </div>

                <div class="form-group" style="position: relative;">
                    <label class="text-shadow" for="password">{{ __('PASSWORD') }}</label>
                    <input type="password" class="form-control text-shadow box-shadow @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ __('PASSWORD') }}" value="{{ old('password') }}"  required>
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
                    <button type="submit" class="btn-login btn btn-blank box-shadow w-100 px-3">
                        {{ __('LOGIN') }}
                    </button>
                </div>


                <div class="mt-3 text-center">
                    @if(get_setting('enable_registration'))
                    <!-- রেজিস্ট্রেশন লিঙ্ক (যদি নতুন ইউজার হয়) -->
                    <p style="color: rgba(255, 255, 255, 0.6); font-size: 0.9rem;" class="mb-2">
                        {{ __("Don't have an account?") }}
                        <a href="{{ route('register') }}" style="color: #fff; font-weight: 600; text-decoration: none; border-bottom: 1px solid #fff;">
                            {{ __('Create Account') }}
                        </a>
                    </p>
                    @endif

                    @if(get_setting('enable_forget_password'))
                    <!-- পাসওয়ার্ড ভুলে গেলে -->
                    <p class="mt-2">
                        <a href="{{ route('password.forget') }}" style="color: rgba(255, 255, 255, 0.8); font-size: 0.85rem; text-decoration: none; transition: 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">
                            <i class="fas fa-lock me-1"></i> {{ __('Forgot your password?') }}
                        </a>
                    </p>
                    @endif
                </div>
                <div class="auth-footer mt-4 text-center d-none">
                    <div class="d-flex justify-content-between align-items-center" style="max-width: 300px; margin: 0 auto; border-top: 1px solid rgba(255,255,255,0.1); pt-3">

                        <!-- Registration/Login Toggle -->
                        <a href="{{ route('register') }}" class="text-decoration-none" style="color: #ffffffd1; font-size: 13px;">
                            {{ __('Register') }}
                        </a>

                        <span style="color: rgba(255,255,255,0.2)">|</span>


                        <!-- Forgot Password -->
                        <a href="{{ route('password.forget') }}" class="text-decoration-none" style="color: #ffffffd1; font-size: 13px;">
                            {{ __('Reset Password') }}
                        </a>


                    </div>
                </div>


            </form>
        </div>
    </div>


    @include("me::auth.glass")
    @include("me::auth.css")
    @include("me::auth.js")
@endsection
