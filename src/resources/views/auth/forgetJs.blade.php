@push('js')
<script>
$(document).ready(function () {
    let timerInterval;

    /* ============================================================
    1. TAB UI & DATA RESTORE (ALL FIELDS)
    ============================================================ */
    function applyCenterTabUI(targetId) {
        $('.auth-tab').removeClass('active');
        $(`.auth-tab[data-target="${targetId}"]`).addClass('active');

        if (targetId === 'emailInput') {
            $('#emailInput').removeClass('d-none');
            $('#phoneInput').addClass('d-none');
            localStorage.setItem('fp_type', 'email');
        } else {
            $('#phoneInput').removeClass('d-none');
            $('#emailInput').addClass('d-none');
            localStorage.setItem('fp_type', 'phone');
        }
    }

    // Input ভ্যালু লোকাল স্টোরেজে সেভ করা
    $('#email, #phone, #new_password, #confirm_password').on('input', function() {
        const id = $(this).attr('id');
        localStorage.setItem('fp_' + id, $(this).val());
        if(['new_password', 'confirm_password'].includes(id)) checkPasswordMatch();
    });

    // পেজ লোড হলে সব ডাটা রিস্টোর করা
    function restoreAllData() {
        const savedType = localStorage.getItem('fp_type') || 'email';
        applyCenterTabUI(savedType === 'email' ? 'emailInput' : 'phoneInput');

        const fields = ['email', 'phone', 'new_password', 'confirm_password'];
        fields.forEach(field => {
            const val = localStorage.getItem('fp_' + field);
            if (val) $('#' + field).val(val);
        });

        if($('#confirm_password').val()) checkPasswordMatch();
        checkActiveTimer(); // Timer restore logic
    }

    $('.auth-tab').on('click', function () {
        applyCenterTabUI($(this).data('target'));
    });

    /* ============================================================
    2. STEP 1: SEND OTP
    ============================================================ */
    $('#sendOtpBtn').on('click', function () {
        const type = localStorage.getItem('fp_type') || 'email';
        const identity = (type === 'email') ? $('#email').val() : $('#phone').val();

        if (!identity) return alert('Please enter your ' + type);

        const btn = $(this);
        btn.prop('disabled', true).text('Sending...');

        $.ajax({
            url: "{{ route('password.forget.store') }}",
            method: "POST",
            data: { _token: "{{ csrf_token() }}", type: type, identity: identity },
            success: function (res) {
                const expiryTime = new Date().getTime() + 120000;
                localStorage.setItem('fp_otp_expiry', expiryTime);

                $('#send-otp-container').hide();
                // $('.auth-tabs').hide(); // Removed: Tab container hide hobena
                $('#otp-section').slideDown();
                $('#timer-display').show();
                startTimer(expiryTime);
            },
            error: function (xhr) {
                btn.prop('disabled', false).text('SEND OTP');
                alert(xhr.responseJSON?.message || 'Error sending OTP');
            }
        });
    });

    /* ============================================================
    3. STEP 2: VERIFY OTP
    ============================================================ */
    $('#verifyOtpBtn').on('click', function() {
        const otp = $('#otp').val();
        if (otp.length !== 6) return alert('Enter 6-digit OTP');

        const btn = $(this);
        btn.prop('disabled', true).text('Verifying...');

        $.ajax({
            url: "{{ route('password.verify.otp') }}",
            method: "POST",
            data: { _token: "{{ csrf_token() }}", otp: otp },
            success: function(res) {
                $('#otp-section').hide();
                $('#timer-display').hide();
                $('#reset-password-section').slideDown();
                // Inputs disable kore deya hoyeche jate change korte na pare
                $('#email, #phone').prop('disabled', true);
            },
            error: function(xhr) {
                btn.prop('disabled', false).text('VERIFY OTP');
                alert(xhr.responseJSON?.message || 'Invalid OTP');
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

    $('#toggleNewPassword, #toggleConfirmPassword').on('click', function () {
        const target = $(this).attr('id') === 'toggleNewPassword' ? '#new_password' : '#confirm_password';
        const input = $(target);
        const icon = $(this).find('i');
        input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
        icon.toggleClass('fa-eye fa-eye-slash');
    });

    $('#resetPasswordBtn').on('click', function (e) {
        e.preventDefault();
        const btn = $(this);
        btn.prop('disabled', true).text('Updating...');

        // সিলেক্টেড টাইপ (email/phone) ফর্ম ডেটার সাথে যোগ করা হয়েছে
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

                alert(res.message);
                window.location.href = res.redirect;
            },
            error: function (xhr) {
                btn.prop('disabled', false).text('RESET PASSWORD');
                alert(xhr.responseJSON?.message || 'Error resetting password');
            }
        });
    });

    /* ============================================================
    5. TIMER & INITIALIZE
    ============================================================ */
    function startTimer(expiryTime) {
        clearInterval(timerInterval);
        timerInterval = setInterval(function () {
            const distance = expiryTime - Date.now();
            if (distance <= 0) {
                clearInterval(timerInterval);
                $('#otp-section').hide();
                $('#timer-display').hide();
                $('#send-otp-container').show();
                $('#sendOtpBtn').prop('disabled', false).text('RESEND OTP');
                // Inputs enable korte hobe abar
                $('#email, #phone').prop('disabled', false);
                return;
            }
            const m = Math.floor(distance / 60000);
            const s = Math.floor((distance % 60000) / 1000);
            $('#countdown').text(`${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`);
        }, 1000);
    }

    function checkActiveTimer() {
        const expiry = localStorage.getItem('fp_otp_expiry');
        if (expiry && expiry - Date.now() > 0) {
            $('#send-otp-container').hide();
            // $('.auth-tabs').hide(); // Removed
            $('#otp-section').show();
            $('#timer-display').show();
            startTimer(parseInt(expiry));
            // OTP step active thakle input disable thakbe
            $('#email, #phone').prop('disabled', true);
        }
    }

    // Initialize Page
    restoreAllData();
});
</script>
@endpush
