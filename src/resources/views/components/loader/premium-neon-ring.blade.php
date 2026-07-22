<!-- Premium Loader 3: Neon Progress Ring -->
<div id="pl-neon-ring" class="pl-neon-ring-overlay" aria-hidden="true">
    <div class="pl-neon-ring-wrap">
        <svg class="pl-neon-ring-svg" viewBox="0 0 100 100">
            <defs>
                <linearGradient id="pl-neon-ring-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#4285f4" />
                    <stop offset="50%" stop-color="#9b51e0" />
                    <stop offset="100%" stop-color="#00f2fe" />
                </linearGradient>
            </defs>
            <circle class="pl-neon-ring-track" cx="50" cy="50" r="42" />
            <circle class="pl-neon-ring-progress" cx="50" cy="50" r="42" />
        </svg>
        <span class="pl-neon-ring-dot"></span>
    </div>
</div>

<style>
    .pl-neon-ring-overlay {
        position: fixed;
        inset: 0;
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(10, 12, 20, 0.55);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity .35s ease, visibility .35s ease;
    }
    .pl-neon-ring-overlay.active {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
    .pl-neon-ring-wrap {
        position: relative;
        width: 84px;
        height: 84px;
    }
    .pl-neon-ring-svg {
        width: 100%;
        height: 100%;
        transform: rotate(-90deg);
        animation: pl-neon-ring-spin 1.4s linear infinite;
    }
    .pl-neon-ring-track {
        fill: none;
        stroke: rgba(255, 255, 255, 0.12);
        stroke-width: 6;
    }
    .pl-neon-ring-progress {
        fill: none;
        stroke: url(#pl-neon-ring-gradient);
        stroke-width: 6;
        stroke-linecap: round;
        stroke-dasharray: 264;
        stroke-dashoffset: 200;
        filter: drop-shadow(0 0 6px rgba(155, 81, 224, .8));
        animation: pl-neon-ring-dash 1.6s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    }
    @keyframes pl-neon-ring-spin {
        to { transform: rotate(270deg); }
    }
    @keyframes pl-neon-ring-dash {
        0%   { stroke-dashoffset: 240; }
        50%  { stroke-dashoffset: 40; }
        100% { stroke-dashoffset: 240; }
    }
    .pl-neon-ring-dot {
        position: absolute;
        inset: 0;
        margin: auto;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #00f2fe;
        box-shadow: 0 0 12px 3px rgba(0, 242, 254, .8);
        animation: pl-neon-ring-pulse 1.4s ease-in-out infinite;
    }
    @keyframes pl-neon-ring-pulse {
        0%, 100% { transform: scale(.8); opacity: .6; }
        50% { transform: scale(1.2); opacity: 1; }
    }
</style>

<script>
(function () {
    var overlay = document.getElementById('pl-neon-ring');
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
