<!-- Premium Loader 6: Logo Line-Draw (SVG stroke-draw reveal, using me::svg) -->
<div id="pl-svg-draw" class="pl-svg-draw-overlay" aria-hidden="true">
    <div class="pl-svg-draw-canvas" id="pl-svg-draw-canvas">
        @include('me::svg')
    </div>
</div>

<style>
    .pl-svg-draw-overlay {
        position: fixed;
        inset: 0;
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.05) !important;
        backdrop-filter: blur(15px) saturate(160%);
        -webkit-backdrop-filter: blur(15px) saturate(160%);
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transition: opacity .25s ease, visibility 0s linear 0s;
    }
    .pl-svg-draw-overlay.pl-svg-draw-hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity .25s ease, visibility 0s linear .25s;
    }

    .pl-svg-draw-canvas {
        position: relative;
        z-index: 1;
    }
    .pl-svg-draw-canvas svg {
        max-height: none !important;
        width: 220px;
        height: auto;
        filter: drop-shadow(0 6px 18px rgba(15, 155, 214, 0.18));
    }
    .pl-svg-draw-canvas svg path {
        fill-opacity: 0;
        stroke-opacity: 0;
        vector-effect: non-scaling-stroke;
    }
    @media (max-width: 480px) {
        .pl-svg-draw-canvas svg { width: 150px; }
    }
</style>

<script>
(function () {
    var overlay = document.getElementById('pl-svg-draw');
    var canvas = document.getElementById('pl-svg-draw-canvas');
    if (!overlay || !canvas) return;

    var svgEl = canvas.querySelector('svg');
    var paths = svgEl ? Array.prototype.slice.call(svgEl.querySelectorAll('path')) : [];

    var COLOR_A = '#0f9bd6'; // left group (M)
    var COLOR_B = '#54075C'; // right group (E) — yellow/gold, since M and E interlock and a clean split isn't possible, this reads better than purple where the two groups blend at the seam

    var drawn = false;
    var hidden = false;
    var safetyTimer = setTimeout(hide, 7000);

    function hide() {
        if (hidden) return;
        hidden = true;
        clearTimeout(safetyTimer);
        overlay.classList.add('pl-svg-draw-hidden');
    }

    function showInstant() {
        if (!hidden) return;
        hidden = false;
        overlay.classList.remove('pl-svg-draw-hidden');
    }

    function assignColors() {
        if (!paths.length) return;

        var centers = [];
        paths.forEach(function (path) {
            try {
                var pbox = path.getBBox();
                centers.push(pbox.x + pbox.width / 2);
            } catch (err) {
                centers.push(null);
            }
        });

        // Find the natural boundary between the two letter clusters instead
        // of guessing a fixed percentage: sort the centers and pick the
        // widest gap between consecutive values (restricted to the middle
        // 60% so a stray outlier near either end can't skew it). That gap
        // is the empty space between the M cluster and the E cluster.
        var known = centers.filter(function (c) { return c !== null; }).sort(function (a, b) { return a - b; });
        var threshold = null;
        if (known.length > 1) {
            var lo = Math.floor(known.length * 0.2);
            var hi = Math.ceil(known.length * 0.8);
            var bestGap = -1;
            for (var i = Math.max(lo, 1); i < Math.min(hi, known.length); i++) {
                var gap = known[i] - known[i - 1];
                if (gap > bestGap) {
                    bestGap = gap;
                    threshold = (known[i] + known[i - 1]) / 2;
                }
            }
        }

        paths.forEach(function (path, i) {
            var center = centers[i];
            var color = (threshold === null || center === null || center < threshold) ? COLOR_A : COLOR_B;
            path.style.stroke = color;
            path.style.fill = color;
        });
    }

    function runDrawAnimation() {
        if (!paths.length) { drawn = true; return; }

        assignColors();

        var n = paths.length;
        var staggerWindow = 220; // ms spread across which strokes start drawing
        var drawDuration = 110;  // ms each stroke takes to draw

        paths.forEach(function (path) {
            var len = 0;
            try { len = path.getTotalLength(); } catch (err) { len = 0; }
            path.style.transition = 'none';
            path.style.strokeDasharray = len;
            path.style.strokeDashoffset = len;
        });

        // Two rAF ticks guarantee the browser has committed the "no transition"
        // starting state before we flip transitions on — avoids the flash/jump
        // a single forced-reflow trick can miss.
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                paths.forEach(function (path, i) {
                    var delay = (i / n) * staggerWindow;
                    path.style.transition = 'stroke-dashoffset ' + drawDuration + 'ms ease-out ' + delay + 'ms, stroke-opacity .2s ease ' + delay + 'ms';
                    path.style.strokeOpacity = '1';
                    path.style.strokeDashoffset = '0';
                });

                var totalDrawTime = staggerWindow + drawDuration;

                setTimeout(function () {
                    paths.forEach(function (path) {
                        path.style.transition = 'fill-opacity .18s ease, stroke-opacity .18s ease';
                        path.style.fillOpacity = '1';
                        path.style.strokeOpacity = '0';
                    });
                    setTimeout(function () {
                        drawn = true;
                        maybeHideAfterDraw();
                    }, 140);
                }, totalDrawTime + 30);
            });
        });
    }

    var pageLoaded = false;
    function maybeHideAfterDraw() {
        if (drawn && pageLoaded) hide();
    }

    function onLoadComplete() {
        pageLoaded = true;
        maybeHideAfterDraw();
    }
    if (document.readyState === 'complete') {
        onLoadComplete();
    } else {
        window.addEventListener('load', onLoadComplete, { once: true });
    }

    runDrawAnimation();

    // Restore from back/forward cache — never leave the loader stuck
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

    // Reappear instantly (already-drawn, no redraw) when actually leaving this tab
    document.addEventListener('click', function (e) {
        if (e.defaultPrevented || e.button !== 0 || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;
        var link = e.target.closest('a');
        if (!isSameTabNavigableLink(link)) return;
        showInstant();
    }, true);

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form || form.classList.contains('no-loader') || form.classList.contains('ajax-form')) return;
        var targetAttr = form.getAttribute('target');
        if (targetAttr && targetAttr !== '_self') return;
        showInstant();
    });

    window.addEventListener('beforeunload', function () {
        showInstant();
    });
})();
</script>
