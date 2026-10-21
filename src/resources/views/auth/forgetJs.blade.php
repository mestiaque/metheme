@push('js')
<script>
$(document).ready(function () {
    /* ============================================================
    1. DATA PERSISTENCE & RESTORE (TAB UI লজিক auth/shared.blade.php এ শেয়ার করা)
    ============================================================ */
    $('#email, #phone, #new_password, #confirm_password').on('input', function () {
        const id = $(this).attr('id');
        const val = $(this).val().trim();

        if (val === '') {
            localStorage.removeItem('fp_' + id);   // 🔑 key remove
        } else {
            localStorage.setItem('fp_' + id, val);
        }

        if (['new_password', 'confirm_password'].includes(id)) {
            checkPasswordMatch();
        }
    });

    // পেজ লোড হলে সব ডাটা রিস্টোর করা
    function restoreAllData() {
        const savedType = localStorage.getItem('fp_type') || 'email';
        applyCenterTabUI(savedType === 'email' ? 'emailInput' : 'phoneInput', 'fp_type');

        const fields = ['email', 'phone', 'new_password', 'confirm_password'];
        fields.forEach(field => {
            const val = localStorage.getItem('fp_' + field);
            if (val) $('#' + field).val(val);
        });

        if ($('#confirm_password').val()) checkPasswordMatch();
        checkActiveTimer(); // Timer restore logic
    }

    $('.auth-tab').on('click', function () {
        // shudhu OTP shotti-i cholle tab lock (key thaka mane-i active na, meyad o dekhte hobe)
        const expiry = parseInt(localStorage.getItem('fp_otp_expiry'), 10);
        if (expiry && expiry - Date.now() > 0) return;
        applyCenterTabUI($(this).data('target'), 'fp_type');
    });


    /* ============================================================
    2. STEP 1: SEND OTP
    ============================================================ */
    $('#sendOtpBtn').on('click', function () {
        const type = localStorage.getItem('fp_type') || 'email';
        const identity = (type === 'email') ? $('#email').val() : $('#phone').val();

        if (!identity) return mAlert('Please enter your ' + type, "info");

        const btn = $(this);
        btn.prop('disabled', true);
        fpSetBtnState('#sendOtpBtn', 'scanning');

        $.ajax({
            url: "{{ route('password.forget.store') }}",
            method: "POST",
            data: { _token: "{{ csrf_token() }}", type: type, identity: identity },
            success: function (res) {
                var fpWait = fpSetBtnState('#sendOtpBtn', 'success') || 0; // scanning shesh howa porjonto opekkha
                const expiryTime = new Date().getTime() + 120000;
                localStorage.setItem('fp_otp_expiry', expiryTime);

                setTimeout(function () {
                    $('#send-otp-container').hide();
                    $('#otp-section').slideDown();
                    $('#timer-display').show();
                    fpSetBtnState('#sendOtpBtn', '');
                }, 700 + fpWait);
                startAuthCountdown(expiryTime, onOtpExpire);
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
    3. STEP 2: VERIFY OTP
    ============================================================ */
    $('#verifyOtpBtn').on('click', function() {
        const otp = $('#otp').val();
        if (otp.length !== 6) return mAlert('Enter 6-digit OTP', "info");

        const btn = $(this);
        btn.prop('disabled', true);
        fpSetBtnState('#verifyOtpBtn', 'scanning');

        $.ajax({
            url: "{{ route('password.verify.otp') }}",
            method: "POST",
            data: { _token: "{{ csrf_token() }}", otp: otp },
            success: function(res) {
                var fpWait = fpSetBtnState('#verifyOtpBtn', 'success') || 0; // scanning shesh howa porjonto opekkha
                setTimeout(function () {
                    $('#otp-section').hide();
                    $('#timer-display').hide();
                    $('#reset-password-section').slideDown();
                    fpSetBtnState('#verifyOtpBtn', '');
                    btn.prop('disabled', false);
                    // Inputs disable kore deya hoyeche jate change korte na pare
                    $('#email, #phone').prop('disabled', true);
                }, 700 + fpWait);
            },
            error: function(xhr) {
                btn.prop('disabled', false);
                fpSetBtnState('#verifyOtpBtn', 'denied');
                setTimeout(function () { fpSetBtnState('#verifyOtpBtn', ''); }, 700);
                mAlert(xhr.responseJSON?.message || 'Invalid OTP', "error");
            }
        });
    });

    /* ============================================================
    4. STEP 3: RESET PASSWORD & UI LOGIC
    ============================================================ */
    function checkPasswordMatch() {
        const pass = $('#new_password').val();
        const confPass = $('#confirm_password').val();
        const confirmInput = $('#confirm_password');

        if (confPass.length > 0) {
            if (pass === confPass) {
                confirmInput.css('border', '2px solid #28a745');
                $('#resetPasswordBtn').prop('disabled', false);
            } else {
                confirmInput.css('border', '2px solid #dc3545');
                $('#resetPasswordBtn').prop('disabled', true);
            }
        } else {
            confirmInput.css('border', '');
        }
    }

    $('#resetPasswordBtn').on('click', function (e) {
        e.preventDefault();
        const btn = $(this);
        btn.prop('disabled', true).text('Updating...');

        // সিলেক্টেড টাইপ (email/phone) ফর্ম ডেটার সাথে যোগ করা হয়েছে
        const formData = $('#forgotPasswordForm').serializeArray();
        const type = localStorage.getItem('fp_type') || 'email';
        formData.push({name: 'type', value: type});

        $.ajax({
            url: "{{ route('password.reset.store') }}",
            method: "POST",
            data: formData, // Serialized array পাঠানো হচ্ছে
            success: function (res) {
                const storageKeys = ['fp_type', 'fp_email', 'fp_phone', 'fp_new_password', 'fp_confirm_password', 'fp_otp_expiry'];
                storageKeys.forEach(key => localStorage.removeItem(key));

                mAlert(res.message, "success");
                window.location.href = res.redirect;
            },
            error: function (xhr) {
                btn.prop('disabled', false).text('RESET PASSWORD');
                mAlert(xhr.responseJSON?.message || 'Error resetting password', "error");
            }
        });
    });

    /* ============================================================
    5. TIMER HELPERS & INITIALIZE
    ============================================================ */
    function onOtpExpire() {
        localStorage.removeItem('fp_otp_expiry'); // 🔑 MUST

        $('#otp-section').hide();
        $('#timer-display').hide();
        $('#send-otp-container').show();
        $('#sendOtpBtn').prop('disabled', false).find('.fp-text').text('RESEND OTP');
        $('#email, #phone').prop('disabled', false);
    }

    function checkActiveTimer() {
        const expiry = localStorage.getItem('fp_otp_expiry');
        if (expiry && expiry - Date.now() > 0) {
            $('#send-otp-container').hide();
            $('#otp-section').show();
            $('#timer-display').show();
            startAuthCountdown(parseInt(expiry), onOtpExpire);
            // OTP step active thakle input disable thakbe
            $('#email, #phone').prop('disabled', true);
        } else if (expiry) {
            // page bondho thakar shomoy meyad shesh hoye gele onOtpExpire chole ni - purono key muche fela,
            // noile tab chirodin lock thake
            localStorage.removeItem('fp_otp_expiry');
        }
    }

    // Initialize Page
    restoreAllData();
});
</script>
@endpush
