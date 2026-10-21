<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'ESTIAQUE') }} | @yield('title', 'Dashboard')</title>

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="@yield('meta-title', 'M. ESTIAQUE')" />
    <meta name="author" content="@yield('meta-author', config('app.name', 'ESTIAQUE'))" />
    <meta name="description" content="@yield('meta-description', 'M. Estiaque Ahmed Khan is a skilled Software Engineer and Full-Stack Web Developer specializing in PHP, Laravel, and modern web technologies. Based in Dhaka, Bangladesh, he creates high-quality web applications and innovative solutions.')" />
    <meta name="keywords" content="@yield('meta-keywords', 'Estiaque, Web Developer, MESTIAQUE')" />
    <link rel="icon" href="{{ get_image('app_ico') ?? asset('assets/img/favicon/Encodex.ico') }}" type="image/x-icon">
    <!--end::Primary Meta Tags-->

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Toastr (admin panel/master.blade.php এর মতোই - top-right টোস্ট) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-bg: #ffffff;
            --secondary-color: #0f9bd6;
            --accent-color: #0f2d4a;
            --focus-color: #0f9bd6;
            --border-color: #cfd8e3;
            --text-muted: #6b7280;
            --shadow-color: rgba(15, 45, 74, 0.15);
        }



        html, body {
            /* height: 100vh; <- eta muche felun */
            min-height: 100vh; /* content beshi holeo background niche parbe */
            margin: 0;
            font-family: 'Nunito', sans-serif, cursive;
            display: flex;
            flex-direction: column;
            color: #0f2d4a;

            /* Background Properties */
            /* background: radial-gradient(
                circle at center,
                rgba(15, 155, 214, 0.1) 0%,
                rgba(255, 255, 255, 0.7) 45%,
                #eef3f8 100%
            ); */
            background-color: #eef3f8; /* Fallback color */
            background-attachment: fixed; /* Scroll korle gradient-ti fixed thakbe */
        }

        /* মাউস প্যারালাক্স ব্যাকগ্রাউন্ড শেপ - মাউস যেদিকে নড়বে, শেপগুলো উল্টো দিকে
           সামান্য নড়ে depth এর অনুভূতি তৈরি করবে (নিজে থেকে অ্যানিমেট হবে না) */
        .parallax-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .parallax-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            will-change: transform;
        }

        .parallax-shape.shape-1 {
            width: 260px;
            height: 260px;
            top: 8%;
            left: 8%;
            background: rgba(15, 155, 214, 0.3);
        }

        .parallax-shape.shape-2 {
            width: 220px;
            height: 220px;
            bottom: 10%;
            right: 10%;
            background: rgba(15, 45, 74, 0.18);
        }

        .parallax-shape.shape-3 {
            width: 160px;
            height: 160px;
            top: 55%;
            left: 72%;
            background: rgba(15, 155, 214, 0.2);
        }

        /* Particles background */
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 0;
            top: 0;
            left: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .click-particle {
            position: fixed;
            top: 0;
            left: 0;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #1d4ed8;
            pointer-events: none;
            will-change: transform, opacity;
        }

        /* Main content container */
        .blank-container {
            position: relative;
            z-index: 1;
            /* flex: 1 0 auto; grow to fill space for sticky footer */
            text-align: center;
            max-width: 500px;
            width: 100% !important;
            margin: auto;
            /* display: flex; */
            /* flex-direction: column; */
            justify-content: center;
            top: 0;
            transform: none; /* no absolute positioning */
            padding: 10px;
        }

        .error-code {
            font-size: 8rem;
            font-weight: bold;
            color: #1d4ed8; /* Encodex primary */
        }

        .error-message {
            font-size: 1.25rem;
            color: #4b5a6b;
        }

        .btn-encodex {
            background-color: #1d4ed8;
            color: #fff;
            transition: 0.3s;
        }

        .btn-encodex:hover {
            background-color: #2563eb;
            color: #fff;
        }

        .encodex-icon {
            font-size: 6rem;
            color: #1d4ed8;
        }

        /* Footer styling */
        .app-footer {
            flex-shrink: 0; /* do not shrink */
            background: #eff7fa;
            padding: 10px 20px;
            width: 100%;
            opacity: 0.6;
            backdrop-filter: blur(15px) saturate(160%);
            -webkit-backdrop-filter: blur(15px) saturate(160%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
            z-index: 1034;
            grid-area: lte-app-header;
            max-width: 100vw;
            border-bottom: 1px solid var(--bs-border-color);
            transition: .3s ease-in-out;
        }

        /* ব্লব (গুয়ি) হোভার বাটন - একটা SVG গুয়ি ফিল্টার দিয়ে ৪টা ব্লব সার্কেল একসাথে
           মিশিয়ে একটা তরল/লিকুইড ফিল আপ ইফেক্ট তৈরি করে। ডিফল্টে বাটন transparent +
           নীল বর্ডার/টেক্সট, হোভার করলে নিচ থেকে নীল ব্লব উঠে এসে পুরোটা ভরে ফেলে আর
           টেক্সট সাদা হয়ে যায়। মার্কআপ (__inner/__blobs/__blob) JS দিয়ে অটো-ইনজেক্ট হয়
           (নিচের স্ক্রিপ্ট দেখুন), তাই .btn-blank যেকোনো পেজে যেভাবে ব্যবহার হচ্ছে সেভাবেই থাকবে */
        .btn-blank {
            --blob-color: #0f9bd6;
            z-index: 1;
            position: relative;
            padding: 12px 28px;
            border-radius: 30px;
            text-align: center;
            text-transform: uppercase;
            color: var(--blob-color);
            font-size: 16px;
            font-weight: 600;
            background-color: transparent;
            outline: none;
            border: none;
            cursor: pointer;
            overflow: visible;
            box-shadow: 0 8px 20px rgba(15, 155, 214, 0.15);
            transition: color 0.3s, box-shadow 0.3s;
        }

        .btn-blank::before {
            content: "";
            z-index: 1;
            position: absolute;
            inset: 0;
            border: 2px solid var(--blob-color);
            border-radius: 30px;
        }

        .btn-blank:hover {
            color: #ffffff;
            box-shadow: 0 6px 18px rgba(15, 155, 214, 0.35);
        }

        .btn-blank__inner {
            z-index: -1;
            overflow: hidden;
            position: absolute;
            inset: 0;
            border-radius: 30px;
            background: #ffffff;
        }

        /* ফায়ারফক্সে goo ফিল্টার overflow:hidden ভেঙে ফেলে, তাই আলাদা একটা কন্টেইনার */
        .btn-blank__blobs {
            position: relative;
            display: block;
            height: 100%;
            filter: url('#btn-blank-goo');
        }

        .btn-blank__blob {
            position: absolute;
            top: 2px;
            width: 25%;
            height: 100%;
            background: var(--blob-color);
            border-radius: 100%;
            transform: translate3d(0, 150%, 0) scale(1.4);
            transition: transform 0.45s;
        }

        .btn-blank__blob:nth-child(1) { left: 0%; transition-delay: 0s; }
        .btn-blank__blob:nth-child(2) { left: 30%; transition-delay: 0.08s; }
        .btn-blank__blob:nth-child(3) { left: 60%; transition-delay: 0.16s; }
        .btn-blank__blob:nth-child(4) { left: 90%; transition-delay: 0.24s; }

        .btn-blank:hover .btn-blank__blob {
            transform: translateZ(0) scale(1.4);
        }





        










        .text-shadow {
            text-shadow: none;
        }

        .box-shadow {
            box-shadow: 0 2px 4px rgba(15, 45, 74, 0.15);
        }

        .alert {
            text-align: center;
            margin-bottom: 20px;
            animation: shake 0.5s 1;
        }

        .alert-danger {
            color: #dc3545;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-5px); }
            40% { transform: translateX(5px); }
            60% { transform: translateX(-5px); }
            80% { transform: translateX(5px); }
        }

        @media (max-width: 768px) {
            .blank-container {
                /* max-width: 90%; */
            }
            .app-footer{
                font-size: 11px !important
            }
            .app-footer .d-none{
                display: inline !important;
            }
        }
    </style>
    @stack('css')
</head>
<body class="">

    <!-- .btn-blank এর গুয়ি/ব্লব হোভার ইফেক্টের জন্য দরকার - CSS এ filter: url('#btn-blank-goo')
         হিসেবে রেফারেন্স করা হয়, নিজে কিছু দেখায় না -->
    <svg width="0" height="0" style="position: absolute;" aria-hidden="true">
        <defs>
            <filter id="btn-blank-goo">
                <feGaussianBlur in="SourceGraphic" result="blur" stdDeviation="10"></feGaussianBlur>
                <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 21 -7" result="goo"></feColorMatrix>
                <feBlend in2="goo" in="SourceGraphic" result="mix"></feBlend>
            </filter>
        </defs>
    </svg>

    <!-- Parallax background shapes -->
    <div class="parallax-bg" aria-hidden="true">
        <span class="parallax-shape shape-1"></span>
        <span class="parallax-shape shape-2"></span>
        <span class="parallax-shape shape-3"></span>
    </div>

    <!-- Particles -->
    <div id="particles-js"></div>

    <!-- Main Content -->
    <div class="blank-container animate__animated animate__fadeIn">
        @yield('content')
    </div>


    <!-- Footer -->
    @include('me::footer')

    <!-- Scripts -->
    <script src="{{ asset('backend/vendor/jquery/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script> --}}

    <script>
        /* ডিফল্টে কোনো পার্টিকেল থাকবে না - শুধু ক্লিক করলে একঝাঁক ডট আঁকা হবে
           যেগুলো ছড়িয়ে গিয়ে ফেইড হয়ে শেষ হয়ে যাবে (একবারের জন্য, লুপ করবে না),
           আর যতক্ষণ কোনো ডট বেঁচে আছে ততক্ষণ মাউস কাছে গেলে সেগুলো সরে যাবে */
        (function () {
            var container = document.getElementById('particles-js');
            if (!container) return;

            var particles = [];

            function spawnBurst(x, y) {
                var count = 8 + Math.floor(Math.random() * 4);
                for (var i = 0; i < count; i++) {
                    var angle = (Math.PI * 2 * i) / count + Math.random() * 0.5;
                    var speed = 0.5 + Math.random() * 1;
                    var el = document.createElement('div');
                    el.className = 'click-particle';
                    container.appendChild(el);
                    particles.push({
                        el: el,
                        x: x,
                        y: y,
                        vx: Math.cos(angle) * speed,
                        vy: Math.sin(angle) * speed,
                        life: 0,
                        maxLife: 700 + Math.random() * 400
                    });
                }
            }

            var lastTime = performance.now();
            function tick(now) {
                var dt = Math.min(now - lastTime, 50);
                lastTime = now;

                for (var i = particles.length - 1; i >= 0; i--) {
                    var p = particles[i];
                    p.life += dt;
                    p.x += p.vx * (dt / 16);
                    p.y += p.vy * (dt / 16);

                    var progress = p.life / p.maxLife;
                    if (progress >= 1) {
                        p.el.remove();
                        particles.splice(i, 1);
                        continue;
                    }

                    var scale = 1 - progress * 0.6;
                    p.el.style.transform = 'translate(' + p.x + 'px,' + p.y + 'px) scale(' + scale + ')';
                    p.el.style.opacity = 1 - progress;
                }

                requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);

            document.addEventListener('click', function (e) {
                if (e.target.closest('a, button, input, textarea, select, .btn-blank, .password-toggle, .custom-checkbox')) return;
                spawnBurst(e.clientX, e.clientY);
            });

            // এখনো বেঁচে থাকা ডটগুলো মাউস কাছে গেলে সরে যাবে (repulse)
            document.addEventListener('mousemove', function (e) {
                var mx = e.clientX, my = e.clientY;
                particles.forEach(function (p) {
                    var dx = p.x - mx, dy = p.y - my;
                    var dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 80 && dist > 0) {
                        var force = (80 - dist) / 80;
                        p.vx += (dx / dist) * force * 0.8;
                        p.vy += (dy / dist) * force * 0.8;
                    }
                });
            });
        })();

        /* .btn-blank এর ভেতরের আসল কনটেন্ট (টেক্সট/আইকন) না সরিয়ে ব্লব মার্কআপ
           অটো-ইনজেক্ট করা হচ্ছে, যাতে প্রতিটা পেজে (login/register/forget/error
           pages) আলাদা করে HTML বদলাতে না হয় */
        (function () {
            document.querySelectorAll('.btn-blank').forEach(function (btn) {
                if (btn.querySelector('.btn-blank__inner')) return; // ইতিমধ্যে ইনজেক্ট করা হয়ে গেছে

                var inner = document.createElement('span');
                inner.className = 'btn-blank__inner';

                var blobs = document.createElement('span');
                blobs.className = 'btn-blank__blobs';

                for (var i = 0; i < 4; i++) {
                    var blob = document.createElement('span');
                    blob.className = 'btn-blank__blob';
                    blobs.appendChild(blob);
                }

                inner.appendChild(blobs);
                btn.appendChild(inner);
            });
        })();

        /* ব্যাকগ্রাউন্ড শেপ প্যারালাক্স - নিজে থেকে কিছু অ্যানিমেট হবে না, শুধু মাউস
           নড়লে শেপগুলো কেন্দ্র থেকে মাউসের দূরত্ব অনুযায়ী উল্টো দিকে সরে depth অনুভূতি তৈরি করবে */
        (function () {
            var parallaxShapes = document.querySelectorAll('.parallax-shape');

            var rafId = null;
            var lastEvent = null;

            function onMouseMove(e) {
                lastEvent = e;
                if (rafId) return;
                rafId = requestAnimationFrame(update);
            }

            function update() {
                rafId = null;
                var e = lastEvent;
                if (!e) return;

                var cx = window.innerWidth / 2, cy = window.innerHeight / 2;
                var dx = (e.clientX - cx) / cx;
                var dy = (e.clientY - cy) / cy;
                parallaxShapes.forEach(function (shape, i) {
                    var depth = (i + 1) * 10;
                    shape.style.transform = 'translate(' + (-dx * depth) + 'px, ' + (-dy * depth) + 'px)';
                });
            }

            document.addEventListener('mousemove', onMouseMove);
        })();
    </script>
    @include('me::components.fileDrop')
    @stack('js')
    @include('me::components.lmAlert')
</body>
</html>
