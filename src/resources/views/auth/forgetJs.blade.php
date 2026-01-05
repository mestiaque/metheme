@push('js')
<script>
$(document).ready(function () {
    let timerInterval;

    /* ============================================================
    1. TAB TOGGLE LOGIC (EMAIL/PHONE)
    ============================================================ */
    function applyCenterTabUI(targetId) {
        const tabsContainer = $('.auth-tabs');
        const wrapper = $('.tab-wrapper');
        const targetLabel = $(`.auth-tab[data-target="${targetId}"]`);
        if (!targetLabel.length) return;

        // Active class
        $('.auth-tab').removeClass('active');
        targetLabel.addClass('active');

        // Centering calculation
        const containerCenter = tabsContainer.width() / 2;
        const labelOffsetLeft = targetLabel.position().left;
        const labelHalfWidth = targetLabel.outerWidth() / 2;
        const labelCenterInWrapper = labelOffsetLeft + labelHalfWidth;
        const translateX = containerCenter - labelCenterInWrapper;
        wrapper.css('transform', `translateX(${translateX}px)`);

        // Show/hide inputs
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

    $('.auth-tab').on('click', function () {
        const target = $(this).data('target');
        applyCenterTabUI(target);
    });

    /* ============================================================
    2. RESTORE STATE (TAB & TIMER)
    ============================================================ */
    function restoreState() {
        const savedType = localStorage.getItem('fp_type') || 'email';
        const targetId = (savedType === 'email') ? 'emailInput' : 'phoneInput';
        setTimeout(() => applyCenterTabUI(targetId), 200);

        // Restore OTP timer if active
        checkActiveTimer();
    }

    /* ============================================================
    3. SEND OTP LOGIC
    ============================================================ */
    $('#sendOtpBtn').on('click', function () {
        const type = localStorage.getItem('fp_type') || 'email';
        const identity = (type === 'email') ? $('#email').val() : $('#phone').val();

        if (!identity) {
            alert('Please enter your ' + (type === 'email' ? 'email' : 'phone number'));
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).text('Sending...');

        $.ajax({
            url: "{{ route('password.forget.store') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                type: type,
                identity: identity
            },
            success: function (res) {
                const expiryTime = new Date().getTime() + 120000; // 2 minutes
                localStorage.setItem('fp_otp_expiry', expiryTime);
                localStorage.setItem('fp_identity', identity);
                localStorage.setItem('fp_type', type);

                $('#send-otp-container').hide();
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
    4. RESET PASSWORD (OTP verified internally)
    ============================================================ */
    $('#resetPasswordBtn').on('click', function (e) {
        e.preventDefault();
        const password = $('#new_password').val();
        const password_confirmation = $('#confirm_password').val();
        const otp = $('#otp').val();

        if (!password || !password_confirmation) {
            return alert('Enter new password and confirm it');
        }

        if (password !== password_confirmation) {
            return alert('Passwords do not match');
        }

        if (!otp || otp.length !== 6) {
            return alert('Enter 6-digit OTP');
        }

        $.ajax({
            url: "{{ route('password.reset.store') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                otp: otp,
                password: password,
                password_confirmation: password_confirmation
            },
            success: function (res) {
                // Clear localStorage
                ['fp_otp_expiry', 'fp_identity', 'fp_type'].forEach(k => localStorage.removeItem(k));
                alert(res.message);
                window.location.href = res.redirect;
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message || 'Error resetting password');
            }
        });
    });

    /* ============================================================
    5. OTP TIMER
    ============================================================ */
    function startTimer(expiryTime) {
        clearInterval(timerInterval);
        timerInterval = setInterval(function () {
            const distance = expiryTime - Date.now();
            if (distance <= 0) {
                clearInterval(timerInterval);
                resetToResendView();
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
            $('#otp-section').show();
            $('#timer-display').show();
            startTimer(parseInt(expiry));
        }
    }

    function resetToResendView() {
        $('#otp-section').hide();
        $('#timer-display').hide();
        $('#send-otp-container').show();
        $('#sendOtpBtn').prop('disabled', false).text('RESEND OTP');
    }

    /* ============================================================
    6. INIT
    ============================================================ */
    restoreState();

    $(window).on('resize', function() {
        const currentType = localStorage.getItem('fp_type') || 'email';
        applyCenterTabUI(currentType === 'email' ? 'emailInput' : 'phoneInput');
    });
});
</script>
@endpush
