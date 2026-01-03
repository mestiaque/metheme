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

    <style>
        :root {
            --primary-bg: #0f2d4a;
            --secondary-color: #0f9bd6;
            --accent-color: #ffffff;
            --focus-color: #007bff;
            --border-color: #002472;
            --text-muted: #ffffff7a;
            --shadow-color: rgba(0, 0, 0, 0.4);
        }

        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Nunito', sans-serif, cursive;
            display: flex;
            flex-direction: column;
            background: #0f172a; /* dark login-style background */
            color: #fff;
        }

        /* Particles background */
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 0;
            top: 0;
            left: 0;
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
            color: #cbd5e1;
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
            background: #0f9ad627;
            padding: 10px 20px;
            width: 100%;
            opacity: 0.9;
        }

        .btn-blank {
            position: relative;
            background-color: #0f2d4a6e;
            color: var(--accent-color);
            border: 1px solid #002472;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.35s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            text-transform: uppercase;
        }

        .btn-blank::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,0.25), transparent);
            transition: all 0.6s ease;
        }

        .btn-blank:hover {
            background-color: rgba(0, 68, 255, 0.171) !important;
            color: #ffffff;
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 6px 15px rgba(0,230,38,0.45), 0 0 0 2px rgba(0,230,38,0.25);
        }

        .btn-blank:hover::before {
            left: 100%;
        }

        .btn-blank:active {
            transform: translateY(-1px) scale(0.98);
            box-shadow: 0 6px 14px rgba(0,230,38,0.35);
        }

        .text-shadow {
            text-shadow: 2px 2px 5px rgba(0,0,0,0.5);
        }

        .box-shadow {
            box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .alert {
            text-align: center;
            margin-bottom: 20px;
            text-shadow: 0px 3px 4px rgb(255 5 8 / 70%);
            animation: shake 0.5s 1;
        }

        .alert-danger {
            color: #ff0000a1;
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
<body>
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
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script>
        /* Particles.js config */
        particlesJS("particles-js", {
            "particles": {
                "number": { "value": 80, "density": { "enable": true, "value_area": 800 } },
                "color": { "value": "#1d4ed8" },
                "shape": { "type": "circle" },
                "opacity": { "value": 0.5, "random": true },
                "size": { "value": 3, "random": true },
                "line_linked": { "enable": true, "distance": 150, "color": "#1d4ed8", "opacity": 0.4, "width": 1 },
                "move": { "enable": true, "speed": 2, "direction": "none", "random": true, "straight": false, "out_mode": "out" }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": { "onhover": { "enable": true, "mode": "repulse" }, "onclick": { "enable": true, "mode": "push" } },
                "modes": { "repulse": { "distance": 100 }, "push": { "particles_nb": 4 } }
            },
            "retina_detect": true
        });
    </script>
    @stack('js')
</body>
</html>
