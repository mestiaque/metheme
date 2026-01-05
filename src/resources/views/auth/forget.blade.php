@extends('me::blank')
@section('title', 'Forgot Password')
@section('meta-title', 'Forgot Password')
@section('content')
    <div class="login-card">
        <div class="login-form">
            <div class="login-header mb-0">
                <div class="login-avatar">
                    <img loading="lazy" src="{{ asset('assets/img/favicon/Encodex.ico') }}" class="brand-image opacity-75 shadow" style="width: 100%" alt="mESTIAQUE">
                </div>
                <h1 class="login-title text-shadow">{{ __('FORGOT PASSWORD') }}</h1>
                <p style="text-align: center; color:#ffffff7a" class="text-shadow">{{ __('Reset your password by verifying your account.') }}</p>
            </div>

            <!-- Error/Status Messages -->
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form id="forgotPasswordForm" class="submitForm">
                @csrf

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

                <!-- Step 1: Send OTP Button Container -->
                <div id="send-otp-container" class="mt-3">
                    <button type="button" id="sendOtpBtn" data-form="forgetPass" class="btn btn-blank box-shadow w-100">
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

                    <button type="button" id="verifyOtpBtn" class="btn-login btn btn-blank box-shadow mt-2">
                        {{ __('VERIFY OTP') }}
                    </button>
                </div>

                <!-- Step 3: Reset Password Section (Initially Hidden) -->
                <div id="reset-password-section" style="display: none;" class="mt-3">
                    <div class="form-group" style="position: relative;">
                        <label class="text-shadow" for="new_password">{{ __('NEW PASSWORD') }}</label>
                        <input type="password" spellcheck="false" class="form-control text-shadow box-shadow" id="new_password" name="new_password" placeholder="{{ __('NEW PASSWORD') }}" required>
                        <span class="password-toggle text-shadow" id="toggleNewPassword" style="position: absolute; right: 15px; top: 41px; cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>

                    <div class="form-group" style="position: relative;">
                        <label class="text-shadow" for="confirm_password">{{ __('CONFIRM PASSWORD') }}</label>
                        <input type="password" spellcheck="false" class="form-control text-shadow box-shadow" id="confirm_password" name="confirm_password" placeholder="{{ __('CONFIRM PASSWORD') }}" required>
                        <span class="password-toggle text-shadow" id="toggleConfirmPassword" style="position: absolute; right: 15px; top: 41px; cursor: pointer;">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>

                    <button type="submit" id="resetPasswordBtn" class="btn-login btn btn-blank box-shadow mt-2 w-100">
                        {{ __('RESET PASSWORD') }}
                    </button>
                </div>

                <div class="text-center mt-3">
                    <p style="color:#ffffff7a" class="text-shadow">
                        {{ __('Remembered your password?') }} <a href="{{ route('login') }}" style="color: #fff; text-decoration: underline;">{{ __('Login') }}</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    @include("me::auth.css")
    @include("me::auth.forgetJs")

@endsection

