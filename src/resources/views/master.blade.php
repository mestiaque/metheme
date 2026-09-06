<!doctype html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
        <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
        <meta name="title" content="@yield('meta-title', config('me_settings.meta_title'))" />
        <meta name="author" content="@yield('meta-author', config('me_settings.meta_author'))" />
        <meta name="description" content="@yield('meta-description', config('me_settings.meta_description'))" />
        <meta name="keywords" content="@yield('meta-keywords', config('me_settings.meta_keywords'))" />
        <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
        <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
        <link rel="preload" href="{{ asset('css/adminlte.min.css') }}" as="style" />
        <title> @yield('title', 'Dashboard') | {{ config('app.name', 'ESTIAQUE') }}</title>

        <style>html,body{background:#fff;}</style>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print" onload="this.media='all'" />
        <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}" />
        <link href="{{ asset('backend/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
        <link rel="icon" href="{{ get_image('app_ico') ?? asset('assets/img/favicon/Encodex.ico') }}" type="image/x-icon">
        <link href="{{ asset('css/form-styles.css') }}" rel="stylesheet">
        <link href="{{ asset('css/encodex.css') }}?v={{ filemtime(public_path('css/encodex.css')) }}" rel="stylesheet">
        <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
        @include('me::components.scrollbar-theme')

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" media="print" onload="this.media='all'" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" media="print" onload="this.media='all'" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" media="print" onload="this.media='all'" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" media="print" onload="this.media='all'" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" media="print" onload="this.media='all'">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.0/summernote-bs5.min.css" rel="stylesheet">

        <style>
            @media (max-width: 767.98px) {
                .hide-mobile{ display: none !important}
            }

            /* Date, Time, Week, Month সবগুলোর আইকন একসাথে পরিবর্তন করার জন্য */
            input[type="date"]::-webkit-calendar-picker-indicator,
            input[type="time"]::-webkit-calendar-picker-indicator,
            input[type="datetime-local"]::-webkit-calendar-picker-indicator,
            input[type="month"]::-webkit-calendar-picker-indicator,
            input[type="week"]::-webkit-calendar-picker-indicator {
                filter: invert(0.5); /* আপনার পছন্দমতো ভ্যালু পরিবর্তন করুন */
                cursor: pointer;
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
                min-height: 2.2rem !important
            }
            .breadcrumb-title {
                font-size: 1.05rem;
                margin: 0;
                font-weight: 600;
                /* background: linear-gradient(90deg, #05266b, #5a65ff); */
                color: #0f9bd6;
                -webkit-background-clip: text;
                /* color: transparent; */
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
            .glass-breadcrumb .btn{
                padding: 0.25rem 0.5rem !important;
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
            .breadcrumb-title .dev-svg{

            }

            /* মোবাইল ভিউর জন্য ড্রপডাউন ম্যাজিক */
            @media (max-width: 767px) {

                .glass-search-form {
                    background: rgba(255, 255, 255, 0.15); /* হালকা সাদা স্বচ্ছতা */
                    backdrop-filter: blur(12px); /* ব্যাকগ্রাউন্ড ব্লার */
                    -webkit-backdrop-filter: blur(12px);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    border-radius: 15px;
                    padding: 0px;
                    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
                }

                .glass-search-form .form-control {
                    background: rgba(255, 255, 255, 0.1);
                    border: 1px solid rgba(255, 255, 255, 0.3);
                    color: #333; /* আপনার থিমের সাথে সামঞ্জস্য রেখে পরিবর্তন করুন */
                    backdrop-filter: blur(4px);
                }
                .glass-search-form .row {
                    display: none; /* শুরুতে লুকানো থাকবে */
                    transition: all 0.4s ease-in-out;
                }

                .glass-search-form.active .row {
                    display: flex;
                    margin-top: 15px;
                }

                /* মোবাইল ট্রিগার বাটন (Glass Button style) */
                .glass-search-form::before {
                    content: "\f002  Tap to Filter Options";
                    font-family: "Font Awesome 5 Free", "Font Awesome 6 Free"; /* ভার্সন অনুযায়ী */
                    font-weight: 900; /* আইকন দেখানোর জন্য এটি জরুরি */
                    display: block;
                    text-align: center;
                    padding: 5px;
                    background: #e2e7eb;
                    color: #000;
                    border-radius: 10px;
                    cursor: pointer;
                    font-weight: 600;
                    text-transform: uppercase;
                    font-size: 13px;
                    letter-spacing: 1px;
                }
                .glass-search-form .btn{
                    float: inline-end;
                }

                .hide-mobile {
                    display: none !important;
                }

            }
        </style>

        @stack('css')
        @stack('style')
        @include('me::components.loader.premium-svg-draw')
        {{-- @include('me::components.loader.premium-skeleton') --}}
        {{-- @include('me::components.loader.premium-dot-wave') --}}
        {{-- @include('me::components.loader.premium-logo-pulse') --}}
        {{-- @include('me::components.loader.premium-neon-ring') --}}
        {{-- @include('me::components.loader.premium-blur-focus') --}}


    </head>

    <body class="layout-fixed sidebar-expand-lg sidebar-mini bg-body-tertiary app-loaded">
    {{-- @include('me::loader') --}}
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
                                @include('me::svg2') <b>@yield('title')</b>
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
        <script src="{{ asset('js/adminlte.min.js') }}?v={{ filemtime(public_path('js/adminlte.min.js')) }}"></script>
        <script src="{{ asset('backend/vendor/jquery/jquery.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.0/summernote-bs5.min.js"></script>
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

        <!-- Summernote Init Script -->
        <script>
            $(function () {
                function initSummernote($el) {
                    if ($el.data('summernote-init')) return;
                    $el.data('summernote-init', true);
                    $el.summernote({
                        height: 120,
                        toolbar: [
                            ['style', ['bold', 'italic', 'underline', 'strikethrough']],
                            ['para', ['ul', 'ol']],
                            ['insert', ['picture', 'link']],
                            ['misc', ['undo', 'redo']]
                        ]
                    });
                }

                // Textareas already visible on page load.
                $('.summernote').not('.modal .summernote').each(function () {
                    initSummernote($(this));
                });

                // Textareas inside a Bootstrap modal: init only once the modal
                // is actually shown. Summernote measures toolbar/editor widths
                // at init time, so initializing inside a display:none modal
                // produces a collapsed, broken layout.
                $(document).on('shown.bs.modal', '.modal', function () {
                    $(this).find('.summernote').each(function () {
                        initSummernote($(this));
                    });
                });
            });
        </script>

        <!-- Summernote Content Lightbox: click an image inside rendered
             summernote HTML (e.g. a table cell showing a saved description)
             to view it full-size, since such images are usually shown
             thumbnail-sized. -->
        <script>
            $(document).on('click', '.summernote-content img', function () {
                var overlay = $(
                    '<div class="summernote-lightbox-overlay">' +
                        '<img src="' + $(this).attr('src') + '">' +
                    '</div>'
                );
                $('body').append(overlay);
                requestAnimationFrame(function () { overlay.addClass('show'); });
                overlay.on('click', function () {
                    overlay.removeClass('show');
                    setTimeout(function () { overlay.remove(); }, 200);
                });
            });
        </script>
        <style>
            .summernote-content img {
                cursor: zoom-in;
            }
            .summernote-lightbox-overlay {
                position: fixed;
                inset: 0;
                z-index: 999998;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(0, 0, 0, 0);
                opacity: 0;
                transition: opacity .2s ease, background .2s ease;
                cursor: zoom-out;
            }
            .summernote-lightbox-overlay.show {
                background: rgba(0, 0, 0, 0.85);
                opacity: 1;
            }
            .summernote-lightbox-overlay img {
                max-width: 90vw;
                max-height: 90vh;
                border-radius: 8px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            }
        </style>

        <!-- Input Focus Script -->
        <script>
            document.addEventListener('focus', function(e) {
                const target = e.target;
                // Only act on number inputs that are not disabled or readonly
                if (
                    target.tagName === 'INPUT' &&
                    target.type === 'number' &&
                    !target.disabled &&
                    !target.readOnly
                ) {
                    // Clear if the current value is exactly 0
                    if (parseFloat(target.value) === 0) {
                        target.value = '';
                    }
                }
            }, true); // capture phase so it works on dynamically added inputs


            document.addEventListener('blur', function(e) {
                const target = e.target;

                // Only act on number inputs that are not disabled or readonly
                if (
                    target.tagName === 'INPUT' &&
                    target.type === 'number' &&
                    !target.disabled &&
                    !target.readOnly
                ) {
                    // Restore 0 if the input is empty
                    if (target.value === '') {
                        target.value = 0;
                    }
                }
            }, true); // use capture so it works on dynamically added inputs
        </script>
        <script>
            $(document).ready(function() {
                // ফর্মের ছদ্ম-বাটনে ক্লিক করলে ড্রপডাউন কাজ করবে
                $('.glass-search-form').on('click', function(e) {
                    console.log( e.target === this);
                    // শুধুমাত্র মোবাইল ভিউতে এবং ফর্মের ভেতর ক্লিক না হলে (যাতে ইনপুট ফিল্ডে ক্লিক করলে বন্ধ না হয়)
                    if (window.innerWidth < 768 && e.target === this) {
                        $(this).toggleClass('active');
                        $(this).attr('class');
                        console.log($(this).attr('class'));
                    }
                });

                // অথবা সহজভাবে: ড্রপডাউন বাটনের মতো কাজ করার জন্য
                // $(document).on('click', '.glass-search-form', function(e){
                //     if(window.innerWidth < 768){
                //         // এই অংশটি ফর্মের বাইরের অংশে ক্লিক চেক করে কাজ করবে
                //         console.log('Clicked on .glass-search-form');
                //         if (!$(e.target).closest('.row').length) {
                //             $(this).toggleClass('active');
                //         }
                //     }
                // });
            });
        </script>

        @stack('js')
        @stack('scripts')
    </body>
</html>
