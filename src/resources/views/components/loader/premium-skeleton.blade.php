<!-- Premium Loader 2: Gradient Skeleton Screen -->
<div id="pl-skeleton" class="pl-skeleton-overlay" aria-hidden="true">
    <div class="pl-skeleton-sidebar">
        <div class="pl-skeleton-brand">
            <span class="pl-skeleton-block pl-skeleton-block--brand-logo"></span>
        </div>
        <div class="pl-skeleton-nav">
            <span class="pl-skeleton-block pl-skeleton-block--item"></span>
            <span class="pl-skeleton-block pl-skeleton-block--item"></span>
            <span class="pl-skeleton-block pl-skeleton-block--item"></span>
            <span class="pl-skeleton-block pl-skeleton-block--item"></span>
            <span class="pl-skeleton-block pl-skeleton-block--item"></span>
            <span class="pl-skeleton-block pl-skeleton-block--item pl-skeleton-block--short"></span>
        </div>
    </div>
    <div class="pl-skeleton-main">
        <div class="pl-skeleton-topbar">
            <span class="pl-skeleton-block pl-skeleton-block--icon"></span>
            <span class="pl-skeleton-block pl-skeleton-block--pill"></span>
            <span class="pl-skeleton-block pl-skeleton-block--avatar"></span>
        </div>
        <div class="pl-skeleton-content">
            <span class="pl-skeleton-block pl-skeleton-block--title"></span>
            <div class="pl-skeleton-cards">
                <span class="pl-skeleton-block pl-skeleton-block--card"></span>
                <span class="pl-skeleton-block pl-skeleton-block--card"></span>
                <span class="pl-skeleton-block pl-skeleton-block--card"></span>
            </div>
            <span class="pl-skeleton-block pl-skeleton-block--row"></span>
            <span class="pl-skeleton-block pl-skeleton-block--row"></span>
            <span class="pl-skeleton-block pl-skeleton-block--row pl-skeleton-block--short"></span>
        </div>
    </div>
</div>

<style>
    .pl-skeleton-overlay {
        position: fixed;
        inset: 0;
        z-index: 999999;
        background: #fff;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity .35s ease, visibility .35s ease;
        display: flex;
    }
    .pl-skeleton-overlay.active {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
    .pl-skeleton-block {
        display: block;
        border-radius: 8px;
        background: linear-gradient(90deg, rgba(120,140,170,.10) 25%, rgba(120,140,170,.22) 37%, rgba(120,140,170,.10) 63%);
        background-size: 400% 100%;
        animation: pl-skeleton-shimmer 1.4s ease-in-out infinite;
    }
    @keyframes pl-skeleton-shimmer {
        0% { background-position: 100% 50%; }
        100% { background-position: 0 50%; }
    }

    /* Sidebar — matches .sidebar-glass: 250px wide, edge-to-edge, rgba(255,255,255,.05) glass */
    .pl-skeleton-sidebar {
        width: 250px;
        flex: 0 0 auto;
        height: 100%;
        display: flex;
        flex-direction: column;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(15px) saturate(150%);
        -webkit-backdrop-filter: blur(15px) saturate(150%);
        border-right: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 10px 0 30px rgba(0, 0, 0, 0.06);
    }
    .pl-skeleton-brand {
        height: 3.56rem;
        flex: 0 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid rgba(160, 208, 255, 0.4);
    }
    .pl-skeleton-block--brand-logo { width: 33px; height: 33px; border-radius: 8px; }
    .pl-skeleton-nav {
        padding: 14px 12px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .pl-skeleton-block--item { height: 40px; width: 100%; border-radius: 12px; }
    .pl-skeleton-block--short { width: 65%; }

    /* Main column */
    .pl-skeleton-main {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    /* Header — matches .header-glass: full width, rgba(255,255,255,.05) glass, ~56px */
    .pl-skeleton-topbar {
        height: 56px;
        flex: 0 0 auto;
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 0 20px;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(15px) saturate(160%);
        -webkit-backdrop-filter: blur(15px) saturate(160%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    .pl-skeleton-block--icon { width: 24px; height: 24px; border-radius: 6px; }
    .pl-skeleton-block--pill { width: 220px; height: 16px; margin-left: auto; }
    .pl-skeleton-block--avatar { width: 32px; height: 32px; border-radius: 50%; flex: 0 0 auto; }

    .pl-skeleton-content {
        flex: 1 1 auto;
        padding: 24px 28px;
        display: flex;
        flex-direction: column;
        gap: 18px;
        overflow: hidden;
    }
    .pl-skeleton-block--title { width: 220px; height: 22px; }
    .pl-skeleton-cards {
        display: flex;
        gap: 16px;
    }
    .pl-skeleton-block--card { flex: 1 1 0; height: 90px; }
    .pl-skeleton-block--row { height: 14px; width: 100%; }
    @media (max-width: 767px) {
        .pl-skeleton-sidebar { display: none; }
    }
</style>

<script>
(function () {
    var overlay = document.getElementById('pl-skeleton');
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
