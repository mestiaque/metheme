<!-- Top line loader, Gemini style: a flowing blue → purple → rose gradient with a soft glow and shimmer,
     trickling towards the end while the page loads (like Google / YouTube) -->
<div id="me-topline" class="me-topline" data-me-loader aria-hidden="true">
    <div class="me-topline-track">
        <div class="me-topline-slide">
            <div class="me-topline-glow"></div>
            <div class="me-topline-line"></div>
        </div>
    </div>
</div>

<style>
    .me-topline {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 10px;              /* 3px line + room for the glow below it */
        z-index: 9999999;
        pointer-events: none;
    }
    .me-topline-track {
        position: absolute;
        inset: 0;
        overflow: hidden;
    }
    /* the full-width line slides in from the left as progress grows (NProgress technique):
       GPU-smooth, and the gradient never stretches */
    .me-topline-slide {
        position: absolute;
        inset: 0;
        transform: translateX(-100%);
        transition: transform 400ms cubic-bezier(.4, 0, .2, 1);
        will-change: transform;
    }
    .me-topline-line,
    .me-topline-glow {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        /* Gemini colours, repeated so the flow loops seamlessly */
        background: linear-gradient(90deg, #4285f4, #9b72cb, #d96570, #9b72cb, #4285f4, #9b72cb, #d96570, #9b72cb, #4285f4);
        background-size: 200% 100%;
        animation: me-topline-flow 2.4s linear infinite;
    }
    .me-topline-line {
        height: 3px;
        border-radius: 0 3px 3px 0;
    }
    /* light shimmer travelling along the line */
    .me-topline-line::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 140px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .75), transparent);
        animation: me-topline-shimmer 1.6s ease-in-out infinite;
    }
    .me-topline-glow {
        height: 6px;
        top: 0;
        filter: blur(5px);
        opacity: .65;
    }
    @keyframes me-topline-flow {
        from { background-position: 0% 0; }
        to   { background-position: -200% 0; }
    }
    @keyframes me-topline-shimmer {
        from { transform: translateX(-160px); }
        to   { transform: translateX(100vw); }
    }
    @media (prefers-reduced-motion: reduce) {
        .me-topline-line, .me-topline-glow, .me-topline-line::after { animation: none; }
        .me-topline-track { transition: none; }
    }
</style>

{{-- show/hide: shared with every loader --}}
@include('me::components.loader._controller')

<script>
// Progress only (0 -> ~90% while loading, 100% when done); showing/hiding is the shared controller's job
(function () {
    var slide = document.querySelector('#me-topline .me-topline-slide');
    if (!slide || !window.MELoader) return;

    var progress = 0;
    var trickleTimer = null;

    function setProgress(value, duration) {
        progress = Math.min(Math.max(value, 0), 1);
        slide.style.transitionDuration = duration + 'ms';
        slide.style.transform = 'translateX(' + (progress * 100 - 100).toFixed(2) + '%)';
    }

    // Each step closes part of the remaining gap to 90%: fast at first, ever slower near the end
    function trickle() {
        if (progress < 0.9) {
            setProgress(progress + (0.9 - progress) * (0.06 + Math.random() * 0.08), 450);
        }
        trickleTimer = setTimeout(trickle, 250 + Math.random() * 250);
    }

    function start() {
        clearTimeout(trickleTimer);
        setProgress(0, 0);
        void slide.offsetWidth;            // apply the reset before animating again
        setProgress(0.12, 250);
        trickleTimer = setTimeout(trickle, 250);
    }

    function finish() {
        clearTimeout(trickleTimer);
        setProgress(1, 220);
    }

    start();
    MELoader.onShow(start);
    MELoader.onHide(finish);
})();
</script>
