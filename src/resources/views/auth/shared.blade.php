@push('css')
<style>
    /* ============================================================
       SEND OTP বাটন - পেপার-প্লেন (.fp-send-btn, সাথে .btn-blank এর ব্লব
       হোভার থাকছেই)। login এর fp-login-btn এর মতোই shrink-to-circle,
       কারণ এটাও w-100 (ফুল-উইথ) - #send-otp-container কে flex করে
       সেন্টার করা হচ্ছে যাতে margin:auto এর মতো snap না করে
       ============================================================ */
    #send-otp-container {
        display: flex;
        justify-content: center;
    }

    .fp-send-btn {
        position: relative;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: width 0.5s cubic-bezier(.4,0,.2,1), background-color 0.3s, color 0.3s;
    }

    /* bootstrap .w-100 nijei width:100% !important dey, tai ekhane o !important lagbe */
    .fp-send-btn.scanning,
    .fp-send-btn.success,
    .fp-send-btn.denied {
        width: 56px !important;
        padding: 0 !important;
        pointer-events: none;
    }

    .fp-send-btn.scanning,
    .fp-send-btn.success,
    .fp-send-btn.denied {
        background-color: #ffffff !important; /* bg সাদা থাকবে, state বর্ডার-কালারে বোঝা যাবে */
    }

    .fp-send-btn.scanning::before,
    .fp-send-btn.success::before,
    .fp-send-btn.denied::before {
        opacity: 1;
        border-color: var(--blob-color);
    }
    .fp-send-btn.success::before { border-color: #1fb466; }
    .fp-send-btn.denied::before { border-color: #d93b3b; }

    /* bg সাদা হওয়ায় প্লেন আইকনের সাদা স্ট্রোক আর দেখা যাবে না - state কালারে বদলানো হচ্ছে */
    .fp-send-btn .fp-icon-wrap svg g {
        stroke: var(--blob-color);
    }
    .fp-send-btn.success .fp-icon-wrap svg g { stroke: #1fb466; }
    .fp-send-btn.denied .fp-icon-wrap svg g { stroke: #d93b3b; }

    .fp-send-btn.scanning .btn-blank__inner,
    .fp-send-btn.success .btn-blank__inner,
    .fp-send-btn.denied .btn-blank__inner {
        opacity: 0;
    }

    .fp-send-btn .fp-text {
        transition: opacity 0.25s, transform 0.25s;
    }

    .fp-send-btn.scanning .fp-text,
    .fp-send-btn.success .fp-text,
    .fp-send-btn.denied .fp-text {
        opacity: 0;
        transform: scale(0.6);
    }

    .fp-send-btn .fp-icon-wrap {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 32px;
        height: 32px;
        transform: translate(-50%, -50%);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
    }

    /* success e o plane-i thakbe, shudhu button er rong sobuj hobe */
    .fp-send-btn.scanning .fp-icon-wrap,
    .fp-send-btn.success .fp-icon-wrap,
    .fp-send-btn.denied .fp-icon-wrap {
        opacity: 1;
    }

    /* svg te width/height deya nei - container puro bhorbe */
    .fp-send-btn .fp-icon-wrap svg,
    .fp-send-btn .fp-sent-ok svg,
    .fp-verify-btn .fp-icon-wrap svg {
        display: block;
        width: 100%;
        height: 100%;
        overflow: visible;
    }

    .fp-send-btn .pl-plane {
        stroke-dasharray: 300;
        stroke-dashoffset: 300;
        transition: stroke-dashoffset 700ms ease 150ms;
    }

    .fp-send-btn.scanning .pl-plane,
    .fp-send-btn.success .pl-plane,
    .fp-send-btn.denied .pl-plane {
        stroke-dashoffset: 0;
    }

    @keyframes fp-plane-pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    /* path er mathata bam dike (15,50) - send er jonno dan dike ghorano */
    .fp-send-btn .fp-icon-wrap svg {
        transform: scaleX(-1);
    }

    .fp-send-btn.scanning .fp-icon-wrap svg {
        animation: fp-plane-pulse 1s ease-in-out 700ms infinite;
    }

    /* scanning e JS button disable kore - bootstrap er .btn:disabled opacity .65 e jhapsha na hoy */
    .fp-send-btn:disabled,
    .fp-verify-btn:disabled {
        opacity: 1 !important;
    }

    /* সফল হলে - প্লেনটা উড়ে চলে যাওয়ার মতো সরে গিয়ে fade, তারপর চেকমার্ক */
    /* checkmark (.fp-sent-ok) ar dekhano hoy na - icon bodlay na */
    .fp-send-btn .fp-sent-ok {
        display: none;
    }

    .fp-send-btn.denied {
        animation: fp-shake 0.45s;
    }

    @keyframes fp-shake {
        0%, 100% { transform: translateX(0); }
        20% { transform: translateX(-6px); }
        40% { transform: translateX(6px); }
        60% { transform: translateX(-4px); }
        80% { transform: translateX(4px); }
    }

    /* ============================================================
       VERIFY OTP / REGISTER NOW বাটন - প্যাডলক আনলক (.fp-verify-btn)।
       এই বাটনগুলো auto-width (w-100 নেই), তাই .btn-encodex-save এর মতোই
       কন্টেইনার সংকুচিত না করে ভেতরেই আইকন ওভারলে করা হচ্ছে
       ============================================================ */
    #otp-section {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    #otp-section > .form-group {
        align-self: stretch;
    }

    .fp-verify-btn {
        position: relative;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        transition: width 0.5s cubic-bezier(.4,0,.2,1), padding 0.5s cubic-bezier(.4,0,.2,1), background-color 0.3s, color 0.3s;
    }

    .fp-verify-btn .fp-text {
        transition: opacity 0.2s, transform 0.2s;
    }

    .fp-verify-btn.scanning .fp-text,
    .fp-verify-btn.success .fp-text,
    .fp-verify-btn.denied .fp-text {
        opacity: 0;
        transform: scale(0.7);
    }

    .fp-verify-btn .fp-icon-wrap {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 32px;
        height: 32px;
        transform: translate(-50%, -50%);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s;
    }

    .fp-verify-btn.scanning .fp-icon-wrap,
    .fp-verify-btn.success .fp-icon-wrap,
    .fp-verify-btn.denied .fp-icon-wrap {
        opacity: 1;
    }

    .fp-verify-btn.scanning,
    .fp-verify-btn.success,
    .fp-verify-btn.denied {
        width: 56px !important;
        padding: 0 !important;
        color: transparent !important;
        pointer-events: none;
    }

    .fp-verify-btn.scanning::before,
    .fp-verify-btn.success::before,
    .fp-verify-btn.denied::before {
        opacity: 1;
        border-color: var(--blob-color);
    }
    .fp-verify-btn.success::before { border-color: #1fb466; }
    .fp-verify-btn.denied::before { border-color: #d93b3b; }

    .fp-verify-btn.scanning,
    .fp-verify-btn.success,
    .fp-verify-btn.denied {
        background-color: #ffffff !important; /* bg সাদা থাকবে, state বর্ডার-কালারে বোঝা যাবে */
    }

    /* bg সাদা হওয়ায় প্যাডলকের সাদা স্ট্রোক আর দেখা যাবে না - state কালারে বদলানো হচ্ছে */
    .fp-verify-btn .fp-icon-wrap svg g {
        stroke: var(--blob-color);
    }
    .fp-verify-btn.success .fp-icon-wrap svg g { stroke: #1fb466; }
    .fp-verify-btn.denied .fp-icon-wrap svg g { stroke: #d93b3b; }

    .fp-verify-btn.scanning .btn-blank__inner,
    .fp-verify-btn.success .btn-blank__inner,
    .fp-verify-btn.denied .btn-blank__inner {
        opacity: 0;
    }

    .fp-verify-btn .pl-body,
    .fp-verify-btn .pl-shackle {
        stroke-dasharray: 300;
        stroke-dashoffset: 300;
        transition: stroke-dashoffset 700ms ease 150ms;
    }

    .fp-verify-btn .pl-shackle {
        transition: stroke-dashoffset 600ms ease 400ms, transform 0.4s cubic-bezier(.34,1.56,.64,1);
        transform-origin: 65% 48%; /* SVG viewBox 0-100 এর সাথে px মিলবে না, % সব ব্রাউজারে নিরাপদ */
    }

    .fp-verify-btn.scanning .pl-body,
    .fp-verify-btn.success .pl-body,
    .fp-verify-btn.denied .pl-body,
    .fp-verify-btn.scanning .pl-shackle,
    .fp-verify-btn.success .pl-shackle,
    .fp-verify-btn.denied .pl-shackle {
        stroke-dashoffset: 0;
    }

    @keyframes fp-lock-pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    .fp-verify-btn.scanning .fp-icon-wrap svg {
        animation: fp-lock-pulse 1s ease-in-out 800ms infinite;
    }

    /* সফল - শ্যাকল স্প্রিং করে খুলে যাবে (আনলক) */
    .fp-verify-btn.success .pl-shackle {
        transform: translate(-6px, -10px) rotate(-25deg);
    }

    /* ব্যর্থ - লক করা অবস্থাতেই shake */
    .fp-verify-btn.denied {
        animation: fp-shake 0.45s;
    }
</style>
@endpush

@push('js')
<script>
(function () {
    let timerInterval;

    /* ============================================================
    .fp-send-btn / .fp-verify-btn এর scanning/success/denied স্টেট টগল
    - registration.blade.php ও forget.blade.php দুই জায়গার OTP হ্যান্ডলার
    থেকেই কল হয়, তাই একবার এখানে রাখা হলো
    ============================================================ */
    // login er MIN_SCAN_MS er moto: AJAX joto druto i asuk, scanning animation
    // (shrink + icon draw-in) puro dekhabe, denied o ektu dhore rakhbe
    var FP_MIN_SCAN_MS = 1100;
    var FP_MIN_DENIED_MS = 700; // success/denied koto khon dhore rakhbe

    function fpApplyState(el, state) {
        var busy = el.classList.contains('scanning') || el.classList.contains('success') || el.classList.contains('denied');

        // width:auto/100% theke 56px e transition hoy na, tai age asol width px e lock kora
        if (state && !busy) {
            el._fpWidth = el.offsetWidth;
            el.style.width = el._fpWidth + 'px';
            void el.offsetWidth;
        }

        el.classList.remove('scanning', 'success', 'denied');
        if (state) el.classList.add(state);

        // idle e ferar shomoy 56px theke ager width e animate kore tarpor inline width chhere dewa
        if (!state && busy) {
            el.style.width = (el._fpWidth || el.scrollWidth) + 'px';
            clearTimeout(el._fpWidthTimer);
            el._fpWidthTimer = setTimeout(function () { el.style.width = ''; }, 520);
        }
    }

    window.fpSetBtnState = function (selector, state) {
        var el = document.querySelector(selector);
        if (!el) return;

        var now = Date.now();
        el._fpTimers = el._fpTimers || [];
        if (state === 'scanning') {
            el._fpTimers.forEach(clearTimeout); // notun scan - ager queue batil
            el._fpTimers = [];
            el._fpReadyAt = now + FP_MIN_SCAN_MS;
            fpApplyState(el, state);
            return;
        }

        // queue: protita state ager tar por e boshbe, tai denied -> '' kono ta hariye jay na
        var delay = Math.max((el._fpReadyAt || 0) - now, 0);
        el._fpReadyAt = now + delay + (state ? FP_MIN_DENIED_MS : 0); // success/denied o dekha jabe
        el._fpTimers.push(setTimeout(function () { fpApplyState(el, state); }, delay));
        return delay;
    };

    /* ============================================================
    registration/forget er mAlert() ke login er moto toastr toast e dekhano.
    lmAlert er function mAlert @stack('js') er pore load hoy, tai DOMContentLoaded
    e override kora - noile oita abar amar ta ke bodle dito
    ============================================================ */
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof toastr === 'undefined') return; // toastr na thakle ager mAlert-i cholbe
        window.mAlert = function (message, type) {
            type = type || 'success';
            var fn = { success: 'success', error: 'error', info: 'info', warning: 'warning' }[type] || 'info';
            toastr[fn](message);
        };
    });

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
