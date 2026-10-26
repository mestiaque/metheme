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
        {{-- Bangla letters: Source Sans 3 has none, so they come from Hind Siliguri (stack in encodex.css) --}}
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" media="print" onload="this.media='all'" />
        {{-- Bangla digits + ৳ only: Hind Siliguri's ১ is hard to read --}}
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&text=%E0%A7%A6%E0%A7%A7%E0%A7%A8%E0%A7%A9%E0%A7%AA%E0%A7%AB%E0%A7%AC%E0%A7%AD%E0%A7%AE%E0%A7%AF%E0%A7%B3&display=swap" media="print" onload="this.media='all'" />
        <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}" />
        <link href="{{ asset('backend/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
        <link rel="icon" href="{{ get_image('app_ico') ?? asset('assets/img/favicon/Encodex.ico') }}" type="image/x-icon">
        {{-- ?v= changes whenever the file does, so browsers that cache CSS (see public/.htaccess) never keep an old copy --}}
        <link href="{{ asset('css/form-styles.css') }}?v={{ @filemtime(public_path('css/form-styles.css')) }}" rel="stylesheet">
        <link href="{{ asset('css/encodex.css') }}?v={{ @filemtime(public_path('css/encodex.css')) }}" rel="stylesheet">
        <link href="{{ asset('css/dashboard.css') }}?v={{ @filemtime(public_path('css/dashboard.css')) }}" rel="stylesheet">
        {{-- master/header/sidebar layout styles (were inline on every page); ?v= changes when the file does --}}
        <link href="{{ asset('css/me-layout.css') }}?v={{ @filemtime(public_path('css/me-layout.css')) }}" rel="stylesheet">
        @include('me::components.scrollbar-theme')

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" media="print" onload="this.media='all'" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" media="print" onload="this.media='all'" />
        {{-- ApexCharts / SortableJS are loaded only by the views that use them (@pushOnce); jsvectormap isn't used --}}
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" media="print" onload="this.media='all'">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'">
        {{-- Summernote is served from this site (public/vendor/summernote), so the browser keeps it cached --}}
        <link href="{{ asset('vendor/summernote/0.9.0/summernote-bs5.min.css') }}" rel="stylesheet">


        @stack('css')
        @stack('style')
        {{-- Page loader: Configurations → "Show page loader" (off = not sent at all) + which design (me_settings.page_loaders) --}}
        @if(get_setting('enable_page_loader', true))
            @php
                $pageLoaders = config('me_settings.page_loaders', []);
                $pageLoaderView = $pageLoaders[get_setting('page_loader')]
                    ?? $pageLoaders[config('me_settings.default_page_loader')]
                    ?? 'me::components.loader.premium-svg-draw';
            @endphp
            @include($pageLoaderView)
        @endif
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

                // ট্রানজিশন বন্ধ রেখে ক্লাস বসানো হচ্ছে যাতে প্রথম পেইন্টে কোনো শেক/জাম্প না দেখা যায়
                document.body.classList.add('sidebar-preload');

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

                window.addEventListener('load', function () {
                    requestAnimationFrame(function () {
                        document.body.classList.remove('sidebar-preload');
                    });
                });
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
                                {{-- Configurations → Title bar logo: off / uploaded image / a plain icon when no image is uploaded --}}
                                @if(get_setting('show_title_logo', true))
                                    @if($titleLogo = get_image('title_logo'))
                                        <img src="{{ $titleLogo }}" alt="" style="height: 2rem; width: auto; vertical-align: middle;">
                                    @else
                                        {{-- Bootstrap Icons' Bluesky (Font Awesome 5 here has no fa-bluesky; that's FA 6.5+) --}}
                                        <i class="bi bi-bluesky text-primary me-1" style="font-size: 1.4rem; vertical-align: middle;"></i>
                                    @endif
                                @endif
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
        <script src="{{ asset('js/adminlte.min.js') }}?v={{ filemtime(public_path('js/adminlte.min.js')) }}"></script>
        <script src="{{ asset('backend/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/summernote/0.9.0/summernote-bs5.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script src="{{ asset('js/encodex.js') }}?v={{ filemtime(public_path('js/encodex.js')) }}"></script>

        <!-- Print links open their tab with window.open(): browsers only let a script close a tab a script
             opened, so the Close button on printMaster2 can then really remove the tab.
             Ctrl/Shift/middle click keep the browser's own behaviour. -->
        <script>
            document.addEventListener('click', function (e) {
                var link = e.target.closest('a.btn-encodex-print[target="_blank"], a[data-print-tab]');
                if (!link || e.defaultPrevented || e.button !== 0 || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;

                var tab = window.open(link.href, '_blank');
                if (!tab) return;                 // popup blocked: let the link open normally
                tab.opener = null;
                e.preventDefault();
            });
        </script>

        <!-- সাইডবার মিনিমাইজ/এক্সপ্যান্ড স্টেট সেভ - toggle বাটনে সরাসরি বাইন্ড করা
             AdminLTE-র নিজের ক্লিক হ্যান্ডলারের আগে/পরে কবে চলবে তা নিয়ে অনুমান না করে,
             document-এ ডেলিগেট করা হয়েছে: bubble phase এ এটা সবসময় টার্গেট এলিমেন্টে
             সরাসরি বাইন্ড করা AdminLTE-র হ্যান্ডলারের পরেই চলবে, তাই body-র ক্লাস তখন
             ইতিমধ্যে আপডেট হয়ে গেছে থাকে - সেটাই পড়ে localStorage এ সেভ করা হয় -->
        <script>
            document.addEventListener('click', function (e) {
                if (!e.target.closest('[data-lte-toggle="sidebar"]')) return;
                if (window.innerWidth <= 768) return; // মোবাইলে সবসময় collapsed, সেভ করার দরকার নেই

                var collapsed = document.body.classList.contains('sidebar-collapse');
                localStorage.setItem('sidebarState', collapsed ? 'collapsed' : 'open');
            });
        </script>

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
                        const fileLabel = this.parentElement.querySelector('.custom-file-label');
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

        <!-- Summernote: add class="summernote" to any <textarea> and it becomes an editor.
             Options (optional attributes):
               data-height="200"          editor height in px (default 150)
               data-placeholder="..."     placeholder text (or the textarea's placeholder)
               data-images="false"        hide the picture button
             Inside a Bootstrap modal it is initialised when the modal opens. -->
        <script>
            $(function () {
                function initSummernote($el) {
                    if ($el.data('summernote-init') || typeof $.fn.summernote === 'undefined') return;
                    $el.data('summernote-init', true);

                    var insert = ['link'];
                    if (String($el.data('images')) !== 'false') insert.push('picture');

                    $el.summernote({
                        height: parseInt($el.data('height'), 10) || 150,
                        placeholder: $el.data('placeholder') || $el.attr('placeholder') || '',
                        dialogsInBody: true,
                        toolbar: [
                            ['style', ['bold', 'italic', 'underline', 'clear']],
                            ['color', ['forecolor']],
                            ['para', ['ul', 'ol']],
                            ['insert', insert]
                        ]
                    });
                }

                // Also usable from page scripts, e.g. after adding a textarea with JS:
                // window.meSummernote($('#my-textarea'));
                window.meSummernote = function (el) { $(el).each(function () { initSummernote($(this)); }); };

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

        @include('me::components.fileDrop')
        @stack('js')
        @stack('scripts')
    </body>
</html>
