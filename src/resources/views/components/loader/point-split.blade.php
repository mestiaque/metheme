<!-- Universal Liquid Glass Rainbow Top Loader (Enhanced Round Glow Point) -->
<div id="universal-global-loader-container" style="position: fixed; top: 0; left: 0; width: 100%; z-index: 999999; pointer-events: none;">
    <template id="loader-shadow-template">
        <style>
            :host {
                --rainbow-gradient: linear-gradient(90deg, #ff0055, #00ff66, #00ffff, #ff00ff, #ffcc00, #ff0055);
            }
            .loader-wrapper {
                position: relative;
                width: 100%;
                height: 5px; /* আরও প্রফেশনাল ও স্লিক হাইট */
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(10px) saturate(180%);
                -webkit-backdrop-filter: blur(10px) saturate(180%);
                border-bottom: 1px solid rgba(255, 255, 255, 0.08);
                overflow: hidden;
                transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                opacity: 1;
            }
            .loader-wrapper.fade-out {
                opacity: 0;
            }

            /* নতুন আপগ্রেডেড রাউন্ড গ্লোয়িং পয়েন্ট */
            .center-point {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 16px;
                height: 16px;
                background: var(--rainbow-gradient);
                background-size: 200% auto;
                border-radius: 50%; /* পারফেক্ট রাউন্ড শেপ */

                /* মাল্টি-লেয়ার গ্লো এবং থ্রিডি লিকুইড শ্যাডো ইফেক্ট */
                box-shadow: 0 0 20px rgba(0, 255, 255, 0.6),
                            0 0 35px rgba(255, 0, 85, 0.4),
                            inset 0 0 6px rgba(255, 255, 255, 0.9);

                border: 1.5px solid rgba(255, 255, 255, 0.6);
                animation: liquid-pulse 0.9s infinite alternate ease-in-out, rainbow-flow 3s linear infinite;
                z-index: 2;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* পয়েন্টের ভেতরের চকচকে গ্লাস কোর (ফিনিশিং টাচ) */
            .center-point::after {
                content: '';
                width: 5px;
                height: 5px;
                background: rgba(255, 255, 255, 0.95);
                border-radius: 50%;
                box-shadow: 0 0 6px #ffffff;
            }

            .moving-bar {
                position: absolute;
                top: 0;
                left: 50%;
                height: 100%;
                width: 0;
                background: var(--rainbow-gradient);
                background-size: 200% auto;
                transform: translateX(-50%);
                box-shadow: 0 0 15px rgba(255, 255, 255, 0.25);
                animation: expand-out 2.2s cubic-bezier(0.1, 0.8, 0.2, 1) forwards, rainbow-flow 3s linear infinite;
            }

            @keyframes rainbow-flow {
                0% { background-position: 0% 50%; filter: hue-rotate(0deg); }
                100% { background-position: 200% 50%; filter: hue-rotate(360deg); }
            }

            /* স্মুথ রাউন্ড পালস অ্যানিমেশন */
            @keyframes liquid-pulse {
                0% {
                    transform: translate(-50%, -50%) scale(0.8);
                    box-shadow: 0 0 15px rgba(0, 255, 255, 0.5), 0 0 25px rgba(255, 0, 85, 0.3);
                }
                100% {
                    transform: translate(-50%, -50%) scale(1.25);
                    box-shadow: 0 0 25px rgba(0, 255, 255, 0.8), 0 0 45px rgba(255, 0, 85, 0.6);
                }
            }

            @keyframes expand-out {
                0% { width: 0%; opacity: 0.4; }
                12% { width: 8%; opacity: 1; }
                45% { width: 55%; }
                75% { width: 88%; }
                100% { width: 100%; }
            }
        </style>
        <div id="loader-root" class="loader-wrapper">
            <div class="center-point" id="point"></div>
            <div class="moving-bar"></div>
        </div>
    </template>
</div>

<script>
(function() {
    const container = document.getElementById('universal-global-loader-container');
    const template = document.getElementById('loader-shadow-template');
    if (!container || !template) return;

    const shadowRoot = container.attachShadow({mode: 'closed'});
    shadowRoot.appendChild(template.content.cloneNode(true));

    const loaderRoot = shadowRoot.getElementById('loader-root');
    const centerPoint = shadowRoot.getElementById('point');

    // ১.৫ সেকেন্ড পর মাঝখানের প্রিমিয়াম পয়েন্টটি খুব স্মুথলি ফেড-আউট হবে
    setTimeout(() => {
        if(centerPoint) {
            centerPoint.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
            centerPoint.style.opacity = '0';
            centerPoint.style.transform = 'translate(-50%, -50%) scale(0.5)';
            setTimeout(() => centerPoint.style.display = 'none', 400);
        }
    }, 1500);

    // পেজ পুরোপুরি লোড শেষ হলে মেইন বার ভ্যানিশ হবে
    window.addEventListener('load', function() {
        setTimeout(() => {
            if(loaderRoot) {
                loaderRoot.classList.add('fade-out');
                setTimeout(() => container.remove(), 400);
            }
        }, 200);
    });

    window.addEventListener('beforeunload', function() {
        container.style.display = 'none';
    });
})();
</script>
