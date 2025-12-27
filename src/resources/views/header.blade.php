<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body sticky-top shadow bg-encodex">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Start Navbar Links-->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link sidebar-toggle-btn" data-lte-toggle="sidebar" href="#" role="button">
          <i class="bi bi-list"></i>
        </a>
      </li>
      <li class="nav-item d-nonex d-md-block">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
          {{-- <img loading="lazy" src="{{ asset('assets/img/default-img/Encodex_c.png') }}" class="company-logo" alt="Company Logo"> --}}
          <img loading="lazy" src="{{ get_image('app_logo') ?? asset('assets/img/default-img/Encodex_c.png') }}" class="company-logo" alt="Company Logo">
        </a>
      </li>
    </ul>
    <!--end::Start Navbar Links-->

    <!--begin::End Navbar Links-->
    <ul class="navbar-nav ms-auto">

      <!--end::Navbar Search-->

      <!--begin::Messages Dropdown Menu-->
      <li class="nav-item dropdown d-none">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="bi bi-chat-text"></i>
          <span class="navbar-badge badge text-bg-danger">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-end">
          <a href="#" class="dropdown-item">
            <i class="bi bi-envelope me-2"></i> New Messages
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">See All Messages</a>
        </div>
      </li>
      <!--end::Messages Dropdown Menu-->

      <!--begin::Notifications Dropdown Menu-->
      <li class="nav-item dropdown d-none">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="bi bi-bell-fill"></i>
          <span class="navbar-badge badge text-bg-warning">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="bi bi-envelope me-2"></i> 4 new messages
            <span class="float-end text-secondary fs-7">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="bi bi-people-fill me-2"></i> 8 friend requests
            <span class="float-end text-secondary fs-7">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
            <span class="float-end text-secondary fs-7">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
        </div>
      </li>
      <!--end::Notifications Dropdown Menu-->

      <!--begin::Language Selector-->
      @if(get_setting('enable_translation'))
            @php
                $language = app()->getLocale() ?? 'en';
            @endphp

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="gap: 6px;">
                    @if($language === 'bn')
                        <span style="font-size: 18px;">🇧🇩</span>
                        <span class="hide-mobile">বাংলা</span>
                    @else
                        <span style="font-size: 18px;">🇺🇸</span>
                        <span class="hide-mobile">English</span>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('language.change', 'en') }}" style="gap: 8px;">
                            <span style="font-size: 18px;">🇺🇸</span> English
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('language.change', 'bn') }}" style="gap: 8px;">
                            <span style="font-size: 18px;">🇧🇩</span> বাংলা
                        </a>
                    </li>
                </ul>
            </li>
        @endif

        <!--end::Language Selector-->


      <!--begin::Fullscreen Toggle-->
      <li class="nav-item">
        <a class="nav-link fullscreen-toggle" href="#" data-lte-toggle="fullscreen">
          <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
          <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
        </a>
      </li>
      <!--end::Fullscreen Toggle-->

      <!--begin::User Menu Dropdown-->
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
            @if(Auth::check())
                @if(Auth::user()->profile_image)
                    <img class="user-image rounded-circle shadow"
                        src="{{ route('profile_img.show', Auth::user()->profile_image) }}">
                @else
                    <img class="user-image rounded-circle shadow"
                        src="{{ asset('backend/img/undraw_profile.svg') }}">
                @endif
            @else
                <img class="user-image rounded-circle shadow"
                    src="{{ asset('backend/img/undraw_profile.svg') }}">
            @endif
          <span class="d-none d-md-inline user-name">{{ Auth::user()->name ?? 'EncodeX' }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li class="py-1">
            <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
              <i class="bi bi-person me-2"></i> @lang("Profile")
            </a>
          </li>
          @can('setting.edit')
          <li class="py-1">
            <a class="dropdown-item" href="{{ route('admin.settings.edit') }}">
              <i class="bi bi-gear me-2"></i> @lang("Settings")
            </a>
          </li>
          @endcan
          <li><hr class="dropdown-divider"></li>
          <li class="pb-1">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="dropdown-item">
                <i class="bi bi-box-arrow-right me-2"></i> @lang("Sign Out")
              </button>
            </form>
          </li>
        </ul>
      </li>
      <!--end::User Menu Dropdown-->
    </ul>
    <!--end::End Navbar Links-->
  </div>
  <!--end::Container-->
</nav>
<!--end::Header-->

<style>
    .company-logo {
        height: 34px;
        width: auto;
        margin-top: -6.51px !important;
        filter: drop-shadow(rgb(255, 255, 255) 2px 3px 8px);
    }
    .fullscreen-toggle i{
        margin-top: -1.1px !important;
    }
    .app-header{
       box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3) !important;
       border: none !important;
       color: white !important;
    }
    .app-header .navbar-nav .nav-link {
        color: white !important;
    }
    .user-name{
        margin-top: -1px !important;
    }
</style>

<style>
    .company-logo {
        /* filter:
            drop-shadow(0 0 8px white)
            drop-shadow(0 0 12px white)
            drop-shadow(0 0 16px white); */
        padding: 2px;
        border-radius: 6px;
    }

    @media (max-width: 767px) {
        .hide-mobile {
            display: none !important;
        }
        .container-fluid{
            padding: 0  ;
        }
    }


</style>


