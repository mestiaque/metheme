@extends('me::blank')
@section('title', '403 Forbidden')
@section('meta-title', '403 Forbidden')
@section('content')
    <div class="login-card">
        <div class="login-form">
            <div class="login-header mb-0">
                <div class="login-avatar">
                    <img loading="lazy" src="{{ asset('assets/img/favicon/Encodex.ico') }}" class="brand-image opacity-75 shadow" style="width: 100%" alt="mESTIAQUE">
                </div>
                <h1 class="login-title text-shadow">{{ __('REGISTER') }}</h1>
                <p style="text-align: center; color:#ffffff7a" class="text-shadow">{{ __('Create your account to get started.') }}</p>
            </div>

            <!-- Error/Status Messages -->
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form id="registrationForm">
                @csrf

                <!-- Name Field -->
                <div class="form-group">
                    <label class="text-shadow" for="name">{{ __('FULL NAME') }}</label>
                    <input type="text" class="form-control text-shadow box-shadow" id="name" name="name" spellcheck="false" value="{{ old('name') }}" placeholder="{{ __('FULL NAME') }}" required autofocus>
                </div>

                <!-- Email Field -->
                {{-- <div class="form-group">
                    <label class="text-shadow" for="email">{{ __('EMAIL ADDRESS') }}</label>
                    <input type="email" class="form-control text-shadow box-shadow" id="email" name="email" value="{{ old('email') }}" placeholder="{{ __('EMAIL ADDRESS') }}" required>
                </div> --}}

                <!-- Tab Labels (Clickable) -->
                <div class="auth-tabs">
                    <div class="tab-wrapper">
                        <label class="auth-tab" data-target="emailInput">{{ __('EMAIL ADDRESS') }}</label>
                        <label class="auth-tab" data-target="phoneInput">{{ __('PHONE NUMBER') }}</label>
                    </div>
                </div>

                <!-- Email Input Group -->
                <div class="form-group auth-field-group" id="emailInput">
                    <input type="email" spellcheck="false" class="form-control text-shadow box-shadow" name="email" id="email" placeholder="example@mail.com">
                </div>

                <!-- Phone Input Group (Hidden by default) -->
                <div class="form-group auth-field-group d-none" id="phoneInput">
                    <input type="tel" spellcheck="false" class="form-control text-shadow box-shadow" name="phone" id="phone" placeholder="0XXXXXXXXXX" pattern="0[0-9]{10}" maxlength="11" inputmode="numeric" title="Phone number must start with 0 and be 11 digits long">
                </div>


                <!-- Password Field -->
                <div class="form-group" style="position: relative;">
                    <label class="text-shadow" for="password">{{ __('PASSWORD') }}</label>
                    <input type="password" spellcheck="false" class="form-control text-shadow box-shadow" id="password" name="password" placeholder="{{ __('PASSWORD') }}" required>
                    <span class="password-toggle text-shadow" id="togglePassword" style="position: absolute; right: 15px; top: 41px; cursor: pointer;">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>

                <!-- Step 1: Send OTP Button Container -->
                <div id="send-otp-container" class="mt-3">
                    <button type="button" id="sendOtpBtn" class="btn btn-blank box-shadow w-100">
                        {{ __('SEND OTP') }}
                    </button>
                </div>

                <!-- Timer Display (Initially Hidden) -->
                <div id="timer-display" class="text-center mt-2" style="color: #ff4d4d; display: none;">
                    {{ __('Resend OTP in:') }} <span id="countdown">02:00</span>
                </div>

                <!-- Step 2: OTP Section (Initially Hidden) -->
                <div id="otp-section" style="display: none;" class="mt-3">
                    <div class="form-group">
                        <label class="text-shadow" for="otp">{{ __('6-DIGIT OTP') }}</label>
                        <input type="text" class="form-control text-shadow box-shadow text-center"
                            style="letter-spacing: 10px; font-size: 20px; font-weight: bold;"
                            id="otp" name="otp" maxlength="6" pattern="\d{6}" inputmode="numeric"
                            placeholder="000000">
                    </div>

                    <button type="submit" id="verifyOtpBtn" class="btn-login btn btn-blank box-shadow mt-2">
                        {{ __('REGISTER NOW') }}
                    </button>
                </div>

                <!-- Divider -->
                <div class="text-center my-4 d-none" style="color: #ffffff7a; position: relative;">
                    <hr style="border-color: #ffffff26;">
                    <span style="position: absolute; top: -12px; background: #222; padding: 0 15px; left: 50%; transform: translateX(-50%);">{{ __('OR') }}</span>
                </div>

                <!-- Google Login -->
                <a href="{{ url('auth/google') }}" class="btn btn-blank box-shadow w-100 mb-3 d-none" style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <i class="fab fa-google"></i> {{ __('Continue with Google') }}
                </a>

                <div class="text-center mt-3">
                    <p style="color:#ffffff7a" class="text-shadow">
                        {{ __('Already have an account?') }} <a href="{{ route('login') }}" style="color: #fff; text-decoration: underline;">{{ __('Login') }}</a>
                    </p>
                </div>
            </form>

        </div>

    </div>


    @include("me::auth.css")
    @include("me::auth.js")

@endsection
