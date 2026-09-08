@push('js')
<script>
(function () {
    let timerInterval;

    /* ============================================================
    ইমেইল/ফোন টগল ট্যাব UI - রেজিস্ট্রেশন ও ফরগেট পাসওয়ার্ড দুই পেজেই হুবহু
    একই রকম ছিল, শুধু localStorage key আলাদা (reg_type / fp_type) - তাই
    সেটা প্যারামিটার করে একটাই কপি এখানে রাখা হচ্ছে
    ============================================================ */
    window.applyCenterTabUI = function (targetId, storageKey) {
        const tabsContainer = $('.auth-tabs');
        const wrapper = $('.tab-wrapper');
        const targetLabel = $(`.auth-tab[data-target="${targetId}"]`);
        const indicator = $('.tab-indicator');

        if (!targetLabel.length) return;

        $('.auth-tab').removeClass('active');
        targetLabel.addClass('active');

        const containerCenter = tabsContainer.width() / 2;
        const labelOffsetLeft = targetLabel.position().left;
        const labelHalfWidth = targetLabel.outerWidth() / 2;
        const labelCenterInWrapper = labelOffsetLeft + labelHalfWidth;
        const translateX = (containerCenter - labelCenterInWrapper);
        wrapper.css('transform', `translateX(${translateX}px)`);

        indicator.css({
            'width': targetLabel.outerWidth() + 'px',
            'left': (containerCenter - labelHalfWidth) + 'px'
        });

        if (targetId === 'emailInput') {
            $('#emailInput').removeClass('d-none');
            $('#phoneInput').addClass('d-none');
            localStorage.setItem(storageKey, 'email');
        } else {
            $('#phoneInput').removeClass('d-none');
            $('#emailInput').addClass('d-none');
            localStorage.setItem(storageKey, 'phone');
        }
    };

    /* ============================================================
    OTP কাউন্টডাউন টাইমার - রেজিস্ট্রেশন ও ফরগেট পাসওয়ার্ড দুই জায়গায়ই
    একই লজিক ছিল, শুধু মেয়াদ শেষে কী হবে তা আলাদা - তাই onExpire callback
    ============================================================ */
    window.startAuthCountdown = function (expiryTime, onExpire) {
        clearInterval(timerInterval);
        timerInterval = setInterval(function () {
            const distance = expiryTime - Date.now();
            if (distance <= 0) {
                clearInterval(timerInterval);
                onExpire();
                return;
            }
            const m = Math.floor(distance / 60000);
            const s = Math.floor((distance % 60000) / 1000);
            $('#countdown').text(`${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`);
        }, 1000);
    };

    /* ============================================================
    পাসওয়ার্ড আই-টগল - লগইন, রেজিস্ট্রেশন, ফরগেট পাসওয়ার্ড সব ফর্মেই
    .form-group এর ভেতরে input + .password-toggle এই একই প্যাটার্ন, তাই
    আলাদা আলাদা #id বাইন্ড না করে একটা delegated handler দিয়েই কাজ চলে
    ============================================================ */
    $(document).on('click', '.password-toggle', function () {
        const input = $(this).closest('.form-group').find('input');
        const icon = $(this).find('i');
        input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
        icon.toggleClass('fa-eye fa-eye-slash');
    });
})();
</script>
@endpush
