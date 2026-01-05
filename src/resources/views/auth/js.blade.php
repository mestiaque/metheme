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
