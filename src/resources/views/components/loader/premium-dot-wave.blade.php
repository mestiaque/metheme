<!-- Premium Loader 4: Dot-Matrix / Equalizer Wave -->
<div id="pl-dot-wave" class="pl-dot-wave-overlay" aria-hidden="true">
    <div class="pl-dot-wave-wrap">
        <span class="pl-dot-wave-bar"></span>
        <span class="pl-dot-wave-bar"></span>
        <span class="pl-dot-wave-bar"></span>
        <span class="pl-dot-wave-bar"></span>
        <span class="pl-dot-wave-bar"></span>
    </div>
</div>

<style>
    .pl-dot-wave-overlay {
        position: fixed;
        inset: 0;
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(12px) saturate(160%);
        -webkit-backdrop-filter: blur(12px) saturate(160%);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity .35s ease, visibility .35s ease;
    }
    @media (prefers-color-scheme: dark) {
        .pl-dot-wave-overlay { background: rgba(16, 18, 24, 0.88); }
    }
    .pl-dot-wave-overlay.active {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
    .pl-dot-wave-wrap {
        display: flex;
        align-items: flex-end;
        gap: 6px;
        height: 40px;
    }
    .pl-dot-wave-bar {
        width: 6px;
        height: 12px;
        border-radius: 4px;
        background: linear-gradient(180deg, #4285f4, #9b51e0);
        animation: pl-dot-wave-anim 1s ease-in-out infinite;
    }
    .pl-dot-wave-bar:nth-child(1) { animation-delay: 0s; }
    .pl-dot-wave-bar:nth-child(2) { animation-delay: .12s; }
    .pl-dot-wave-bar:nth-child(3) { animation-delay: .24s; }
    .pl-dot-wave-bar:nth-child(4) { animation-delay: .36s; }
    .pl-dot-wave-bar:nth-child(5) { animation-delay: .48s; }
    @keyframes pl-dot-wave-anim {
        0%, 100% { height: 12px; opacity: .6; }
        50% { height: 40px; opacity: 1; }
    }
</style>

<script>
(function () {
    var overlay = document.getElementById('pl-dot-wave');
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
