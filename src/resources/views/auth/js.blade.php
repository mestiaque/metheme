@push('js')
<script>
    $(document).ready(function () {
        /* ============================================================
        1. TAB ক্লিক বাইন্ডিং (UI লজিক auth/shared.blade.php এ শেয়ার করা)
        ============================================================ */
        $('.auth-tab').on('click', function () {
            applyCenterTabUI($(this).data('target'), 'reg_type');
        });

        /* ============================================================
        2. DATA PERSISTENCE & RESTORE (Refresh-proof)
        ============================================================ */
        function restoreState() {
            // ১. ট্যাব এবং পজিশন রিস্টোর
            const savedType = localStorage.getItem('reg_type') || 'email';
            const targetId = (savedType === 'email') ? 'emailInput' : 'phoneInput';

            // ব্রাউজারকে পজিশন ক্যালকুলেট করার সময় দেওয়ার জন্য সামান্য ডিলে
            setTimeout(() => applyCenterTabUI(targetId, 'reg_type'), 250);

            // ২. ইনপুট ডাটা রিস্টোর
            if (localStorage.getItem('reg_name')) $('#name').val(localStorage.getItem('reg_name'));
            if (localStorage.getItem('reg_email')) $('#email').val(localStorage.getItem('reg_email'));
            if (localStorage.getItem('reg_phone')) $('#phone').val(localStorage.getItem('reg_phone'));
            if (localStorage.getItem('reg_password')) $('#password').val(localStorage.getItem('reg_password'));

            // ৩. ওটিপি টাইমার চেক
            checkActiveTimer();
        }

        // ইনপুট টাইপ করার সাথে সাথে লোকাল স্টোরেজে সেভ
        $('#name, #email, #phone, #password').on('input', function () {
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
                mAlert('Please fill all fields', "info");
                return;
            }

            const btn = $(this);
            btn.prop('disabled', true);
            fpSetBtnState('#sendOtpBtn', 'scanning');

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
                    var fpWait = fpSetBtnState('#sendOtpBtn', 'success') || 0; // scanning shesh howa porjonto opekkha
                    const expiryTime = new Date().getTime() + 120000; // 2 min
                    localStorage.setItem('otp_expiry', expiryTime);
                    setTimeout(function () {
                        showOtpSection();
                        fpSetBtnState('#sendOtpBtn', '');
                    }, 700 + fpWait);
                    startAuthCountdown(expiryTime, function () {
                        localStorage.removeItem('otp_expiry');
                        resetToResendView();
                    });
                },
                error: function (xhr) {
                    btn.prop('disabled', false);
                    fpSetBtnState('#sendOtpBtn', 'denied');
                    setTimeout(function () { fpSetBtnState('#sendOtpBtn', ''); }, 700);
                    mAlert(xhr.responseJSON?.message || 'Error sending OTP', "error");
                }
            });
        });

        /* ============================================================
        4. VERIFY OTP & REGISTRATION
        ============================================================ */
        $('#registrationForm').on('submit', function (e) {
            e.preventDefault();
            const otp = $('#otp').val();
            if (otp.length !== 6) return mAlert('Enter 6-digit OTP', "info");

            fpSetBtnState('#verifyOtpBtn', 'scanning');

            $.ajax({
                url: "{{ route('otpVerify') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    otp: otp
                },
                success: function (response) {
                    var fpWait = fpSetBtnState('#verifyOtpBtn', 'success') || 0; // scanning shesh howa porjonto opekkha
                    // ক্লিনিং লোকাল স্টোরেজ
                    ['otp_expiry', 'reg_name', 'reg_email', 'reg_phone', 'reg_password', 'reg_type'].forEach(k => localStorage.removeItem(k));
                    setTimeout(function () {
                        window.location.href = response.redirect;
                    }, 700 + fpWait);
                },
                error: function (xhr) {
                    fpSetBtnState('#verifyOtpBtn', 'denied');
                    setTimeout(function () { fpSetBtnState('#verifyOtpBtn', ''); }, 700);
                    mAlert(xhr.responseJSON?.message || 'Invalid OTP', "error");
                }
            });
        });

        /* ============================================================
        5. HELPERS (Timer restore, section toggling)
        ============================================================ */
        function checkActiveTimer() {
            const expiry = localStorage.getItem('otp_expiry');
            if (expiry && expiry - Date.now() > 0) {
                showOtpSection();
                startAuthCountdown(parseInt(expiry), function () {
                    localStorage.removeItem('otp_expiry');
                    resetToResendView();
                });
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
            $('#sendOtpBtn').prop('disabled', false).find('.fp-text').text('RESEND OTP');
        }

        // ইনিশিয়ালাইজেশন
        restoreState();

        // উইন্ডো রিসাইজ করলে পজিশন ঠিক করা
        $(window).on('resize', function() {
            const currentType = localStorage.getItem('reg_type') || 'email';
            applyCenterTabUI(currentType === 'email' ? 'emailInput' : 'phoneInput', 'reg_type');
        });
    });
</script>
@endpush
