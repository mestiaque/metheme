@extends('me::blank')
@section('title', '403 Forbidden')
@section('meta-title', '403 Forbidden')
@section('content')
    <div class="login-card">
        <div class="login-form">
            <div class="login-header">
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
                    <input type="text" class="form-control text-shadow box-shadow" id="name" name="name" value="{{ old('name') }}" placeholder="{{ __('FULL NAME') }}" required autofocus>
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label class="text-shadow" for="email">{{ __('EMAIL ADDRESS') }}</label>
                    <input type="email" class="form-control text-shadow box-shadow" id="email" name="email" value="{{ old('email') }}" placeholder="{{ __('EMAIL ADDRESS') }}" required>
                </div>

                <!-- Password Field -->
                <div class="form-group" style="position: relative;">
                    <label class="text-shadow" for="password">{{ __('PASSWORD') }}</label>
                    <input type="password" class="form-control text-shadow box-shadow" id="password" name="password" placeholder="{{ __('PASSWORD') }}" required>
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
                <div class="text-center my-4" style="color: #ffffff7a; position: relative;">
                    <hr style="border-color: #ffffff26;">
                    <span style="position: absolute; top: -12px; background: #222; padding: 0 15px; left: 50%; transform: translateX(-50%);">{{ __('OR') }}</span>
                </div>

                <!-- Google Login -->
                <a href="{{ url('auth/google') }}" class="btn btn-blank box-shadow w-100 mb-3" style="display: flex; align-items: center; justify-content: center; gap: 10px;">
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


    @include("me::auth.login-css")

    @push('js')

        <script>
            $(document).ready(function () {
                let timerInterval;

                // ১. পেজ লোড হওয়ার সময় ইনপুট ডাটা এবং টাইমার রিস্টোর করা
                restoreFormData();
                checkActiveTimer();

                // ইনপুট ফিল্ডে কিছু লিখলে তা সাথে সাথে localStorage এ সেভ করা
                $('#name, #email, #password').on('input', function() {
                    localStorage.setItem('reg_name', $('#name').val());
                    localStorage.setItem('reg_email', $('#email').val());
                    localStorage.setItem('reg_pass', $('#password').val());
                });

                // ২. পাসওয়ার্ড টগল
                $('#togglePassword').on('click', function () {
                    const passwordInput = $('#password');
                    const icon = $(this).find('i');
                    const isPassword = passwordInput.attr('type') === 'password';
                    passwordInput.attr('type', isPassword ? 'text' : 'password');
                    icon.toggleClass('fa-eye fa-eye-slash');
                });

                // ৩. ওটিপি পাঠানো (Step 1)
                $('#sendOtpBtn').on('click', function () {
                    const name = $('#name').val();
                    const email = $('#email').val();
                    const password = $('#password').val();

                    if (!name || !email || !password) {
                        console.log("Please fill all fields.");
                        return;
                    }

                    $(this).prop('disabled', true).text('Sending...');

                    $.ajax({
                        url: "{{ url('register') }}",
                        method: "POST",
                        data: { _token: "{{ csrf_token() }}", name: name, email: email, password: password },
                        success: function (response) {
                            // ২ মিনিটের ফিক্সড এক্সপায়ারি টাইম সেট করা
                            const expiryTime = new Date().getTime() + 120000;
                            localStorage.setItem('otp_expiry', expiryTime);

                            showOtpSection();
                            startTimer(expiryTime);
                            console.log(response.message);
                        },
                        error: function (xhr) {
                            $('#sendOtpBtn').prop('disabled', false).text('SEND OTP');
                            console.log("Error sending OTP");
                        }
                    });
                });

                // ৪. ওটিপি যাচাই ও রেজিস্ট্রেশন (Step 2)
                $('#registrationForm').on('submit', function (e) {
                    e.preventDefault();
                    const otp = $('#otp').val();

                    $.ajax({
                        url: "{{ route('otpVerify') }}",
                        method: "POST",
                        data: { _token: "{{ csrf_token() }}", otp: otp },
                        success: function (response) {
                            // রেজিস্ট্রেশন সাকসেস হলে সব ডাটা মুছে ফেলা
                            localStorage.removeItem('otp_expiry');
                            localStorage.removeItem('reg_name');
                            localStorage.removeItem('reg_email');
                            localStorage.removeItem('reg_pass');

                            alert('Registration Successful!');
                            window.location.href = response.redirect;
                        },
                        error: function (xhr) {
                            alert(xhr.responseJSON.message || "Invalid OTP");
                        }
                    });
                });

                // ৫. টাইমার ফাংশন (Real-time calculation)
                function startTimer(expiryTime) {
                    clearInterval(timerInterval);
                    timerInterval = setInterval(function () {
                        const now = new Date().getTime();
                        const distance = expiryTime - now;

                        if (distance <= 0) {
                            clearInterval(timerInterval);
                            localStorage.removeItem('otp_expiry');
                            resetToResendView();
                            return;
                        }

                        const minutes = Math.floor(distance / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        $('#countdown').text((minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds);
                    }, 1000);
                }

                // ৬. ডাটা রিস্টোর করার ফাংশন
                function restoreFormData() {
                    if (localStorage.getItem('reg_name')) $('#name').val(localStorage.getItem('reg_name'));
                    if (localStorage.getItem('reg_email')) $('#email').val(localStorage.getItem('reg_email'));
                    if (localStorage.getItem('reg_pass')) $('#password').val(localStorage.getItem('reg_pass'));
                }

                // ৭. একটিভ টাইমার চেক করা
                function checkActiveTimer() {
                    const expiry = localStorage.getItem('otp_expiry');
                    if (expiry) {
                        const now = new Date().getTime();
                        if (expiry - now > 0) {
                            showOtpSection();
                            startTimer(parseInt(expiry));
                        } else {
                            localStorage.removeItem('otp_expiry');
                        }
                    }
                }

                function showOtpSection() {
                    $('#send-otp-container').hide();
                    $('#otp-section').show();
                    $('#timer-display').show();
                }

                function resetToResendView() {
                    $('#otp-section').hide();
                    $('#timer-display').hide();
                    $('#send-otp-container').show();
                    $('#sendOtpBtn').prop('disabled', false).text('RESEND OTP');
                }
            });
        </script>
    @endpush
@endsection
