<!-- Universal Gemini Neon Line Drawing Top Loader -->
<div id="universal-line-loader-container" style="position: fixed; top: 0; left: 0; width: 100vw; z-index: 9999999; pointer-events: none; margin: 0; padding: 0;">
    <template id="line-shadow-template">
        <style>
            /* লিকুইড গ্লাস থিম ব্যাকগ্রাউন্ড বার */
            .loader-wrapper {
                position: relative;
                width: 100%;
                height: 4px; /* স্লিক এবং প্রিমিয়াম লুকের জন্য ৪ পিক্সেল */
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(8px) saturate(180%);
                -webkit-backdrop-filter: blur(8px) saturate(180%);
                border-bottom: 1px solid rgba(255, 255, 255, 0.08);
                overflow: hidden;
                transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1);
                opacity: 1;
            }
            .loader-wrapper.fade-out {
                opacity: 0;
            }

            /* Gemini / Google AI স্টাইল নিওন লাইন ড্রয়িং */
            .drawing-line {
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                width: 0; /* শুরুতে জিরো থাকবে */

                /* গুগল ও জেমিনির মতো আইকনিক নিওন গ্রেডিয়েন্ট */
                background: linear-gradient(90deg, #4285f4, #9b51e0, #ea4335, #fbbc05, #34a853, #00f2fe);
                background-size: 200% auto;

                /* নিওন গ্লো ইফেক্ট */
                box-shadow: 0 0 10px rgba(155, 81, 224, 0.8),
                            0 0 20px rgba(0, 242, 254, 0.5);

                border-radius: 0 4px 4px 0;

                /* ব্রাউজার ট্যাব-স্পিনারের মতো লুপ করে চলবে, লোড শেষ না হওয়া পর্যন্ত */
                animation: draw-loop 1.1s cubic-bezier(0.25, 1, 0.5, 1) infinite,
                           neon-flow 2s linear infinite;
            }

            .drawing-line.is-completing {
                animation: draw-complete 0.35s cubic-bezier(0.25, 1, 0.5, 1) forwards,
                           neon-flow 2s linear infinite;
            }

            /* লাইনের ভেতরের নিওন কালার অনবরত প্রবাহিত (Flow) হওয়ার জন্য */
            @keyframes neon-flow {
                0% { background-position: 0% 50%; filter: hue-rotate(0deg); }
                100% { background-position: 200% 50%; filter: hue-rotate(360deg); }
            }

            /* লোডিং চলাকালে ব্রাউজার ট্যাব লজিকের মতো ইনডেফিনিট ড্রয়িং লুপ */
            @keyframes draw-loop {
                0% { width: 0%; opacity: 0.5; }
                15% { width: 10%; opacity: 1; }
                50% { width: 65%; }
                85% { width: 92%; }
                100% { width: 96%; opacity: 1; }
            }

            /* পেজ লোড শেষ হলে ফাইনাল ফুল-উইডথ কমপ্লিশন */
            @keyframes draw-complete {
                0% { width: 96%; opacity: 1; }
                100% { width: 100%; opacity: 1; }
            }
        </style>

        <div id="loader-root" class="loader-wrapper">
            <div class="drawing-line"></div>
        </div>
    </template>
</div>

<script>
(function() {
    const container = document.getElementById('universal-line-loader-container');
    const template = document.getElementById('line-shadow-template');
    if (!container || !template) return;

    const shadowRoot = container.attachShadow({mode: 'closed'});
    shadowRoot.appendChild(template.content.cloneNode(true));
    const loaderRoot = shadowRoot.getElementById('loader-root');
    const drawingLine = shadowRoot.querySelector('.drawing-line');

    let removed = false;

    // ব্রাউজার ট্যাব-স্পিনারের মতো: window.load ফায়ার হলেই loader stop/fade-out
    function removeOnLoadComplete() {
        if (removed) return;
        removed = true;

        if (drawingLine) {
            drawingLine.classList.add('is-completing');
        }

        setTimeout(() => {
            if (loaderRoot) {
                loaderRoot.classList.add('fade-out');
                setTimeout(() => container.remove(), 500);
            }
        }, 350);
    }

    if (document.readyState === 'complete') {
        removeOnLoadComplete();
    } else {
        window.addEventListener('load', removeOnLoadComplete, { once: true });
    }

    // কি-বোর্ড শর্টকাট বা রিলোড বাগ প্রোটেকশন
    window.addEventListener('beforeunload', function() {
        container.style.display = 'none';
    });
})();
</script>
