<!-- Premium Loader 1: Logo Reveal / Pulse -->
<div id="pl-logo-pulse" class="pl-logo-pulse-overlay" aria-hidden="true">
    <div class="pl-logo-pulse-wrap">
        <span class="pl-logo-pulse-ring"></span>
        <span class="pl-logo-pulse-ring pl-logo-pulse-ring--2"></span>
        <span class="pl-logo-pulse-logo">{{ config('app.name', 'ESTIAQUE') }}</span>
    </div>
</div>

<style>
    .pl-logo-pulse-overlay {
        position: fixed;
        inset: 0;
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(14px) saturate(160%);
        -webkit-backdrop-filter: blur(14px) saturate(160%);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity .35s ease, visibility .35s ease;
    }
    @media (prefers-color-scheme: dark) {
        .pl-logo-pulse-overlay { background: rgba(18, 20, 26, 0.85); }
    }
    .pl-logo-pulse-overlay.active {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
    .pl-logo-pulse-wrap {
        position: relative;
        width: 96px;
        height: 96px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .pl-logo-pulse-ring {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        border: 2px solid #0f9bd6;
        opacity: 0;
        animation: pl-logo-pulse-anim 1.8s cubic-bezier(0.25, 0.8, 0.4, 1) infinite;
    }
    .pl-logo-pulse-ring--2 {
        animation-delay: .6s;
        border-color: #9b51e0;
    }
    @keyframes pl-logo-pulse-anim {
        0%   { transform: scale(.55); opacity: .9; }
        70%  { opacity: 0; }
        100% { transform: scale(1.35); opacity: 0; }
    }
    .pl-logo-pulse-logo {
        position: relative;
        z-index: 1;
        font-weight: 700;
        font-size: 13px;
        letter-spacing: 1px;
        color: #0f9bd6;
        text-transform: uppercase;
        animation: pl-logo-pulse-fade 1.8s ease-in-out infinite;
    }
    @keyframes pl-logo-pulse-fade {
        0%, 100% { opacity: .55; }
        50% { opacity: 1; }
    }
</style>

<script>
(function () {
    var overlay = document.getElementById('pl-logo-pulse');
    if (!overlay) return;

    var shown = false;
    var safetyTimer = null;

    function show() {
        if (shown) return;
        shown = true;
        overlay.classList.add('active');
        clearTimeout(safetyTimer);
        safetyTimer = setTimeout(hide, 8000);
    }

    function hide() {
        shown = false;
        clearTimeout(safetyTimer);
        overlay.classList.remove('active');
    }

    function onLoadComplete() {
        setTimeout(hide, 150);
    }
    if (document.readyState === 'complete') {
        onLoadComplete();
    } else {
        window.addEventListener('load', onLoadComplete, { once: true });
    }

    // Restore from back/forward cache should never leave loader stuck
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) hide();
    });

    function isSameTabNavigableLink(link) {
        if (!link) return false;
        var targetAttr = link.getAttribute('target');
        if (targetAttr && targetAttr !== '_self') return false;
        if (link.hasAttribute('download')) return false;
        if (link.classList.contains('no-loader')) return false;
        var href = link.getAttribute('href');
        if (!href) return false;
        if (href.startsWith('#')) return false;
        if (href.startsWith('javascript:')) return false;
        if (href.startsWith('mailto:') || href.startsWith('tel:')) return false;
        return true;
    }

    document.addEventListener('click', function (e) {
        if (e.defaultPrevented || e.button !== 0 || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;
        var link = e.target.closest('a');
        if (!isSameTabNavigableLink(link)) return;
        show();
    }, true);

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form || form.classList.contains('no-loader') || form.classList.contains('ajax-form')) return;
        show();
    });

    window.addEventListener('beforeunload', function () {
        show();
    });
})();
</script>
