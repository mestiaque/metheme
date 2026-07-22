<!-- Premium Loader 5: Blur-to-Focus Reveal (visible by default, no click wiring needed) -->
<div id="pl-blur-focus" class="pl-blur-focus-overlay" aria-hidden="true">
    <div class="pl-blur-focus-spinner"></div>
</div>

<style>
    .pl-blur-focus-overlay {
        position: fixed;
        inset: 0;
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.35);
        backdrop-filter: blur(28px) saturate(140%);
        -webkit-backdrop-filter: blur(28px) saturate(140%);
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transition: opacity .6s cubic-bezier(0.4, 0, 0.2, 1),
                    backdrop-filter .6s cubic-bezier(0.4, 0, 0.2, 1),
                    -webkit-backdrop-filter .6s cubic-bezier(0.4, 0, 0.2, 1),
                    visibility 0s linear .6s;
    }
    @media (prefers-color-scheme: dark) {
        .pl-blur-focus-overlay { background: rgba(15, 17, 22, 0.35); }
    }
    .pl-blur-focus-overlay.pl-blur-focus-done {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        backdrop-filter: blur(0);
        -webkit-backdrop-filter: blur(0);
    }
    .pl-blur-focus-spinner {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 3px solid rgba(15, 155, 214, 0.2);
        border-top-color: #0f9bd6;
        animation: pl-blur-focus-spin .8s linear infinite;
        transition: opacity .3s ease;
    }
    .pl-blur-focus-done .pl-blur-focus-spinner { opacity: 0; }
    @keyframes pl-blur-focus-spin {
        to { transform: rotate(360deg); }
    }
</style>

<script>
(function () {
    var overlay = document.getElementById('pl-blur-focus');
    if (!overlay) return;

    var done = false;
    var safetyTimer = setTimeout(reveal, 8000);

    function reveal() {
        if (done) return;
        done = true;
        clearTimeout(safetyTimer);
        overlay.classList.add('pl-blur-focus-done');
    }

    function onLoadComplete() {
        setTimeout(reveal, 150);
    }
    if (document.readyState === 'complete') {
        onLoadComplete();
    } else {
        window.addEventListener('load', onLoadComplete, { once: true });
    }

    // Fresh state on every page (including bfcache restores) — never stuck across tabs/pages
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) {
            done = false;
            overlay.classList.remove('pl-blur-focus-done');
            onLoadComplete();
        }
    });
})();
</script>
