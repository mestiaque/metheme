<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'mESTIAQUE') }} | @yield('title', 'GUEST')</title>

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="@yield('meta-title', 'mESTIAQUE | GUEST')" />
    <meta name="author" content="@yield('meta-author', 'Your Company')" />
    <meta name="description" content="@yield('meta-description', 'AdminLTE is a Free Bootstrap 5 Admin Dashboard')" />
    <meta name="keywords" content="@yield('meta-keywords', 'bootstrap 5, admin dashboard, laravel')" />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="{{ asset('css/adminlte.css') }}" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media='all'"
    />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}" />
    <!--end::Required Plugin(AdminLTE)-->

    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />

    <!-- jsvectormap -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
      crossorigin="anonymous"
    />
    <link href="{{ asset('backend/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="icon" href="{{ asset('assets/img/favicon/Encodex.ico') }}" type="image/x-icon">
    <link href="{{ asset('css/form-styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/encodex.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

    @stack('css')

  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg sidebar-mini sidebar-collapsex bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">

      <!-- Include Header Component -->
      @include('components.lte.guest-header')

      <!-- Include Sidebar Component -->

      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->

        <!--end::App Content Header-->

        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <div class="d-flex flex-nowrap align-items-center justify-content-between overflow-auto mb-0 bg-white py-1 px-1 shadow-lg mb-3 mt-1" id="breadcrumb">
                <h1 class="h5 mb-0 text-encodex-secondary">
                    <b>@yield('title')</b>
                </h1>
                <div class="d-flex gap-2 justify-content-end">
                    @stack('buttons')
                </div>
            </div>



            @yield('content')
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->

      <!-- Include Footer Component -->
      @include('components.lte.footer')
    </div>
    <!--end::App Wrapper-->

    <!--begin::Scripts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->

    <!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('js/adminlte.js') }}"></script>
    <!--end::Required Plugin(AdminLTE)-->

    <!-- jQuery -->
    <script src="{{ asset('backend/vendor/jquery/jquery.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- sortablejs -->
    <script
      src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
      crossorigin="anonymous"
    ></script>

    <!-- apexcharts -->
    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
      crossorigin="anonymous"
    ></script>

    <!-- jsvectormap -->
    <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
      integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y="
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
      integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY="
      crossorigin="anonymous"
    ></script>

    <!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

        // Disable OverlayScrollbars on mobile devices to prevent touch interference
        const isMobile = window.innerWidth <= 992;

        if (
          sidebarWrapper &&
          OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
          !isMobile
        ) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->

    <script>
        $(document).ready(function() {
            // Set up Select2
            $('[data-control="select2"]').select2();

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
        // Display file name when selecting a file
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

    <!-- Sidebar Toggle Script -->
    <script>
        $(document).ready(function() {
            if (localStorage.getItem('isSidebarOpen') === 'false') {
                $('body').addClass('sidebar-mini');
                $('body').addClass('sidebar-collapse');
                console.log('Sidebar is minimized hide');
            } else {
                $('body').removeClass('sidebar-mini');
                $('body').removeClass('sidebar-collapse');
            }

            $('.sidebar-toggle-btn').on('click', function() {

                if (localStorage.getItem('isSidebarOpen') === 'true') {
                    localStorage.setItem('isSidebarOpen', 'false');
                    $('body').addClass('sidebar-mini');
                    $('body').addClass('sidebar-collapse');
                } else {
                    localStorage.setItem('isSidebarOpen', 'true');
                    $('body').removeClass('sidebar-mini');
                    $('body').removeClass('sidebar-collapse');
                }
            });

        });
    </script>

    <!-- Custom Scripts -->



    @stack('js')
    @stack('scripts')
    <!--end::Scripts-->
  </body>
  <!--end::Body-->
</html>
</html>
