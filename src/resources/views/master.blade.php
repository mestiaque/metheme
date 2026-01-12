<!doctype html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="color-scheme" content="light dark" />
        <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
        <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
        <meta name="title" content="@yield('meta-title', config('me_settings.meta_title'))" />
        <meta name="author" content="@yield('meta-author', config('me_settings.meta_author'))" />
        <meta name="description" content="@yield('meta-description', config('me_settings.meta_description'))" />
        <meta name="keywords" content="@yield('meta-keywords', config('me_settings.meta_keywords'))" />
        <meta name="supported-color-schemes" content="light dark" />
        <link rel="preload" href="{{ asset('css/adminlte.css') }}" as="style" />
        <title>{{ config('app.name', 'ESTIAQUE') }}  | @yield('title', 'Dashboard')</title>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print" onload="this.media='all'" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
        <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />
        <link href="{{ asset('backend/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
        <link rel="icon" href="{{ get_image('app_ico') ?? asset('assets/img/favicon/Encodex.ico') }}" type="image/x-icon">
        <link href="{{ asset('css/form-styles.css') }}" rel="stylesheet">
        <link href="{{ asset('css/encodex.css') }}?v={{ time() }}" rel="stylesheet">
        <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

        <style>
            @media (max-width: 767.98px) {
                .hide-mobile{ display: none !important}
            }

            body::before {
                content: "";
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(44, 62, 80, 0.05);
                backdrop-filter: blur(50px); /* ঝাপসা ম্যাট ইফেক্ট */
                z-index: -1;
            }
            body::after {
                content: "";
                position: fixed;
                top: -10%;
                right: -10%;
                width: 400px;
                height: 400px;
                border-radius: 50%;
                filter: blur(80px);
                z-index: -2;
            }

            .glass-card{
                background: rgba(255, 255, 255, 0.12) ;
                backdrop-filter: blur(16px) saturate(160%) ;
                -webkit-backdrop-filter: blur(16px) saturate(160%);
                border: 1px solid rgba(255, 255, 255, 0.25);
                border-radius: 16px;
                padding: 10px;
            }

            .table thead tr:first-child th:first-child {
                border-top-left-radius: 8px;
            }
            .table thead tr:first-child th:last-child {
                border-top-right-radius: 8px;
            }
            .table tbody tr:last-child td:first-child {
                border-bottom-left-radius: 8px;
            }
            .table tbody tr:last-child td:last-child {
                border-bottom-right-radius: 8px;
            }


            .table-encodex thead th::after {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(
                    120deg,
                    transparent 20%,
                    rgba(255,255,255,.12),
                    transparent 80%
                );
                pointer-events: none;
            }
            .table-encodex thead th {
                position: relative;
                white-space: nowrap ;
            }

            .glass-bar::after {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(
                    120deg,
                    transparent 20%,
                    rgba(255,255,255,.12),
                    transparent 80%
                );
                pointer-events: none;
            }
            .glass-bar {
                position: relative;
            }



            .glass-breadcrumb {
                background: rgba(255, 255, 255, 0.12);
                backdrop-filter: blur(16px) saturate(160%);
                -webkit-backdrop-filter: blur(16px) saturate(160%);
                border: 1px solid rgba(255, 255, 255, 0.25);
                border-radius: 12px;
                padding: 3px 3px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
                position: relative;
                min-height: 2.9rem !important
            }
            .breadcrumb-title {
                font-size: 1.05rem;
                margin: 0;
                font-weight: 600;
                background: linear-gradient(90deg, #05266b, #5a65ff);
                -webkit-background-clip: text;
                color: transparent;
                letter-spacing: 0.5px;
                margin-left: 5px !important;
            }
            .breadcrumb-actions > * {
                backdrop-filter: blur(10px) !important;
                border-radius: 10px !important;
            }

            .glass-breadcrumb {
                animation: fadeSlide .4s ease;
            }
            @keyframes fadeSlide {
                from {
                    opacity: 0;
                    transform: translateY(-6px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>

        @stack('css')
        @stack('style')

    </head>

    <body class="layout-fixed sidebar-expand-lg sidebar-mini bg-body-tertiary app-loaded">

        <script>
            (function() {
                const savedState = localStorage.getItem('sidebarState');
                const breakpoint = 768;

                if (window.innerWidth > breakpoint) {
                    if (savedState === 'collapsed') {
                        document.body.classList.add('sidebar-collapse');
                        document.body.classList.remove('sidebar-open');
                    } else {
                        document.body.classList.remove('sidebar-collapse');
                        document.body.classList.add('sidebar-open');
                    }
                } else {
                    document.body.classList.add('sidebar-collapse');
                    document.body.classList.remove('sidebar-open');
                }
            })();
        </script>

        <div class="app-wrapper">
            @include('me::header')
            @include('me::sidebar')
            <main class="app-main">
                <div class="app-content">
                    <div class="container-fluid">
                        <div class="glass-breadcrumb d-flex flex-nowrap align-items-center justify-content-between overflow-auto mb-2 mt-1">
                            <h1 class="breadcrumb-title">
                                <b>@yield('title')</b>
                            </h1>

                            <div class="breadcrumb-actions d-flex gap-2 justify-content-end">
                                @stack('buttons')
                            </div>
                        </div>
                        @yield('content')
                    </div>
                </div>
            </main>
            @include('me::footer')
        </div>
        <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous" ></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous" ></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous" ></script>
        <script src="{{ asset('js/adminlte.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('backend/vendor/jquery/jquery.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" crossorigin="anonymous" ></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous" ></script>
        <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js" integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous" ></script>
        <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js" integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous" ></script>

        <script>
            $(document).ready(function() {
                // Set up Select2
                $('[data-control="select2"]').select2();

                $(document).on('select2:open', function() {
                    document.querySelector('.select2-container--open .select2-search__field').focus();
                });

                // Set up AJAX defaults with CSRF token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            });
        </script>

        <!-- Toast Messages -->
        <script>
            @if(session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if(session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            @if($errors->any())
                toastr.error("{{ $errors->first() }}");
            @endif
        </script>

        <!-- File Input Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const fileInputs = document.querySelectorAll('.custom-file-input');
                fileInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        const fileName = this.files[0] ? this.files[0].name : 'Choose file';
                        const fileLabel = this.nextElementSibling;
                        if (fileLabel) {
                            fileLabel.textContent = fileName;
                        }
                    });
                });
            });
        </script>

        <!-- Card Widget Script -->
        <script>
            function getCardId(card) {
                return card.getAttribute('id');
            }

            function saveCardState(cardId, state) {
                const saved = JSON.parse(localStorage.getItem('cardStates') || '{}');
                saved[cardId] = state;
                localStorage.setItem('cardStates', JSON.stringify(saved));
            }

            function getSavedCardStates() {
                return JSON.parse(localStorage.getItem('cardStates') || '{}');
            }

            document.addEventListener('DOMContentLoaded', function () {
                const savedStates = getSavedCardStates();

                // Restore card states
                document.querySelectorAll('.card').forEach(card => {
                    const cardId = getCardId(card);
                    if (!cardId) return;

                    // Initialize the CardWidget using any of the control buttons
                    const collapseBtn = card.querySelector('[data-lte-toggle="card-collapse"]');
                    const maximizeBtn = card.querySelector('[data-lte-toggle="card-maximize"]');

                    let widget = null;
                    if (collapseBtn) widget = new CardWidget(collapseBtn);
                    else if (maximizeBtn) widget = new CardWidget(maximizeBtn);
                    else return;

                    // Apply saved state
                    if (savedStates[cardId] === 'collapsed') {
                        widget.collapse();
                    }

                    if (savedStates[cardId] === 'maximized') {
                        widget.maximize();
                    }
                });

                // Listen for collapse / maximize / minimize events
                document.querySelectorAll('.card').forEach(card => {
                    const cardId = getCardId(card);
                    if (!cardId) return;

                    card.addEventListener('collapsed.lte.card-widget', () => {
                        saveCardState(cardId, 'collapsed');
                    });

                    card.addEventListener('expanded.lte.card-widget', () => {
                        saveCardState(cardId, 'expanded');
                    });

                    card.addEventListener('maximized.lte.card-widget', () => {
                        saveCardState(cardId, 'maximized');
                    });

                    card.addEventListener('minimized.lte.card-widget', () => {
                        saveCardState(cardId, 'expanded');
                    });
                });
            });
        </script>

        @stack('js')
        @stack('scripts')
    </body>
</html>
