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

        /* ============================================================
        1. CENTER SWAPPING TAB LOGIC
        ============================================================ */
        function applyCenterTabUI(targetId) {
            const tabsContainer = $('.auth-tabs');
            const wrapper = $('.tab-wrapper');
            const targetLabel = $(`.auth-tab[data-target="${targetId}"]`);
            const indicator = $('.tab-indicator');

            if (!targetLabel.length) return;

            // ১. একটিভ ক্লাস সেট করা
            $('.auth-tab').removeClass('active');
            targetLabel.addClass('active');

            // ২. নিখুঁত সেন্টারিং ক্যালকুলেশন
            const containerCenter = tabsContainer.width() / 2;
            const labelOffsetLeft = targetLabel.position().left;
            const labelHalfWidth = targetLabel.outerWidth() / 2;
            const labelCenterInWrapper = labelOffsetLeft + labelHalfWidth;

            // wrapper কে সরিয়ে লেবেলকে মাঝখানে আনা
            const adjustment = 10;
            const translateX = (containerCenter - labelCenterInWrapper);
            wrapper.css('transform', `translateX(${translateX}px)`);

            // ৩. ইন্ডিকেটর পজিশন (ইন্ডিকেটর সবসময় মাঝখানে থাকবে, শুধু লেবেলের সমান চওড়া হবে)
            indicator.css({
                'width': targetLabel.outerWidth() + 'px',
                'left': (containerCenter - labelHalfWidth) + 'px'
            });

            // ৪. ইনপুট ফিল্ড শো/হাইড এবং লোকাল স্টোরেজ আপডেট
            if (targetId === 'emailInput') {
                $('#emailInput').removeClass('d-none');
                $('#phoneInput').addClass('d-none');
                localStorage.setItem('reg_type', 'email');
            } else {
                $('#phoneInput').removeClass('d-none');
                $('#emailInput').addClass('d-none');
                localStorage.setItem('reg_type', 'phone');
            }
        }

        // লেবেলে ক্লিক ইভেন্ট
        $('.auth-tab').on('click', function () {
            const target = $(this).data('target');
            applyCenterTabUI(target);
        });

        /* ============================================================
        2. DATA PERSISTENCE & RESTORE (Refresh-proof)
        ============================================================ */
        function restoreState() {
            // ১. ট্যাব এবং পজিশন রিস্টোর
            const savedType = localStorage.getItem('reg_type') || 'email';
            const targetId = (savedType === 'email') ? 'emailInput' : 'phoneInput';

            // ব্রাউজারকে পজিশন ক্যালকুলেট করার সময় দেওয়ার জন্য সামান্য ডিলে
            setTimeout(() => applyCenterTabUI(targetId), 250);

            // ২. ইনপুট ডাটা রিস্টোর
            if (localStorage.getItem('reg_name')) $('#name').val(localStorage.getItem('reg_name'));
            if (localStorage.getItem('reg_email')) $('#email').val(localStorage.getItem('reg_email'));
            if (localStorage.getItem('reg_phone')) $('#phone').val(localStorage.getItem('reg_phone'));
            if (localStorage.getItem('reg_password')) $('#password').val(localStorage.getItem('reg_password'));

            // ৩. ওটিপি টাইমার চেক
            checkActiveTimer();
        }

        // ইনপুট টাইপ করার সাথে সাথে লোকাল স্টোরেজে সেভ
        $('input').on('input', function () {
            const id = $(this).attr('id');
            const val = $(this).val();
            if (id) localStorage.setItem('reg_' + id, val);
        });

        /* ============================================================
        3. SEND OTP LOGIC
        ============================================================ */
        $('#sendOtpBtn').on('click', function () {
            const type = localStorage.getItem('reg_type') || 'email';
            const name = $('#name').val();
            const password = $('#password').val();
            const identity = (type === 'email') ? $('#email').val() : $('#phone').val();

            if (!name || !password || !identity) {
                alert('Please fill all fields');
                return;
            }

            const btn = $(this);
            btn.prop('disabled', true).text('Sending...');

            $.ajax({
                url: "{{ url('register') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: name,
                    password: password,
                    type: type,
                    identity: identity
                },
                success: function (response) {
                    const expiryTime = new Date().getTime() + 120000; // 2 min
                    localStorage.setItem('otp_expiry', expiryTime);
                    showOtpSection();
                    startTimer(expiryTime);
                },
                error: function (xhr) {
                    btn.prop('disabled', false).text('SEND OTP');
                    alert(xhr.responseJSON?.message || 'Error sending OTP');
                }
            });
        });

        /* ============================================================
        4. VERIFY OTP & REGISTRATION
        ============================================================ */
        $('#registrationForm').on('submit', function (e) {
            e.preventDefault();
            const otp = $('#otp').val();
            if (otp.length !== 6) return alert('Enter 6-digit OTP');

            $.ajax({
                url: "{{ route('otpVerify') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    otp: otp
                },
                success: function (response) {
                    // ক্লিনিং লোকাল স্টোরেজ
                    ['otp_expiry', 'reg_name', 'reg_email', 'reg_phone', 'reg_password', 'reg_type'].forEach(k => localStorage.removeItem(k));
                    window.location.href = response.redirect;
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message || 'Invalid OTP');
                }
            });
        });

        /* ============================================================
        5. HELPERS (Timer, Toggle, UI)
        ============================================================ */
        function startTimer(expiryTime) {
            clearInterval(timerInterval);
            timerInterval = setInterval(function () {
                const distance = expiryTime - Date.now();
                if (distance <= 0) {
                    clearInterval(timerInterval);
                    localStorage.removeItem('otp_expiry');
                    resetToResendView();
                    return;
                }
                const m = Math.floor(distance / 60000);
                const s = Math.floor((distance % 60000) / 1000);
                $('#countdown').text(`${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`);
            }, 1000);
        }

        function checkActiveTimer() {
            const expiry = localStorage.getItem('otp_expiry');
            if (expiry && expiry - Date.now() > 0) {
                showOtpSection();
                startTimer(parseInt(expiry));
            }
        }

        function showOtpSection() {
            $('#send-otp-container').hide();
            $('#otp-section').slideDown();
            $('#timer-display').show();
        }

        function resetToResendView() {
            $('#otp-section').hide();
            $('#timer-display').hide();
            $('#send-otp-container').show();
            $('#sendOtpBtn').prop('disabled', false).text('RESEND OTP');
        }

        $('#togglePassword').on('click', function () {
            const input = $('#password');
            const icon = $(this).find('i');
            const isPass = input.attr('type') === 'password';
            input.attr('type', isPass ? 'text' : 'password');
            icon.toggleClass('fa-eye fa-eye-slash');
        });

        // ইনিশিয়ালাইজেশন
        restoreState();

        // উইন্ডো রিসাইজ করলে পজিশন ঠিক করা
        $(window).on('resize', function() {
            const currentType = localStorage.getItem('reg_type') || 'email';
            applyCenterTabUI(currentType === 'email' ? 'emailInput' : 'phoneInput');
        });
    });
</script>
@endpush


@push('css')
    <style>
      .auth-tabs {
            position: relative;
            width: 100%;
            display: flex;
            justify-content: center;
            height: 28px;
            overflow: hidden; /* বাইরের অংশ হাইড রাখবে */
            /* margin-bottom: 1rem !important */
        }

        .tab-wrapper {
            position: absolute;
            display: flex;
            align-items: center;
            gap: 20px;
            left: 0;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }

        .auth-tab {
            cursor: pointer;
            font-size: 11px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.4);
            white-space: nowrap;
            text-transform: uppercase;
            transition: all 0.4s ease;
        }

        .auth-tab.active {
            color: var(--accent-color);
            font-size: 13px;
        }

        .tab-indicator {
            position: absolute;
            bottom: 0;
            height: 2px;
            background: #fff;
            transition: all 0.5s ease;
            box-shadow: 0 0 10px #fff;
        }

    </style>
@endpush
@endsection
