<!--begin::Header-->
<nav class="app-header navbar navbar-expand header-glass sticky-top shadow">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Start Navbar Links-->
    <ul class="navbar-nav d-flex align-items-center flex-row"> <!-- align-items-center এখানে মূল ভূমিকা রাখবে -->
    <li class="nav-item">
        <a class="nav-link sidebar-toggle-btn d-flex align-items-center justify-content-center"
        data-lte-toggle="sidebar"
        href="javascript:void(0)"
        role="button"
        style="height: 40px; width: 40px; line-height: 1;"> <!-- হাইট লোগোর সমান রাখা হয়েছে -->
        <i class="bi bi-list" style="font-size: 1.5rem;"></i>
        </a>
    </li>

    <li class="nav-item d-nonex d-md-block ms-2"> <!-- ms-2 দিয়ে একটু গ্যাপ দেওয়া হয়েছে -->
        <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : route('me.dashboard') }}"
            class="nav-link d-flex align-items-center"
            style="height: 40px; padding:0px !important;"> <!-- প্যাডিং ০ করে দেওয়া হয়েছে যাতে ইমেজ বড় হলে সমস্যা না হয় -->
            @include('me::svg')
            {{-- <img loading="lazy"
                src="{{ get_image('app_logo') ?? asset('assets/img/default-img/Encodex_c.png') }}"
                class="company-logo"
                alt="Company Logo"
                style="max-height: 100%; width: auto; display: block;"> --}}
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
                // dd($language);
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
          <span class="d-none d-md-inline user-name">{{ Auth::user()->name ?? 'M. ESTIAQUE' }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li class="py-1">
            <a class="dropdown-item" href="{{ Route::has('admin.profile.edit') ? route('admin.profile.edit') : route('me.profile.edit') }}">
              <i class="bi bi-person me-2"></i> @lang("Profile")
            </a>
          </li>
          @can('setting.edit')
          <li class="py-1">
            <a class="dropdown-item" href="{{ Route::has('admin.settings.edit') ? route('admin.settings.edit') : route('me.settings.edit') }}">
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
/* মেইন হেডার গ্লাস ইফেক্ট */
.header-glass {
    background: rgba(255, 255, 255, 0.05) !important; /* হালকা ট্রান্সপারেন্ট */
    backdrop-filter: blur(15px) saturate(160%); /* ব্লার ইফেক্ট */
    -webkit-backdrop-filter: blur(15px) saturate(160%);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
}

/* নেভিগেশন লিঙ্ক এবং আইকন স্টাইল */
.app-header .navbar-nav .nav-link {
    color: rgba(16, 7, 70, 0.9) !important;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
    border-radius: 10px;
}

.app-header .navbar-nav .nav-link:hover {
    /* background: rgba(255, 255, 255, 0.1); */
    color: #0f2d4a !important;
    transform: translateY(-1px);
}
.dropdown-menu{
    background: rgba(255, 255, 255, 0.99) !important;
    backdrop-filter: blur(15px) saturate(180%) !important;
    -webkit-backdrop-filter: blur(15px) saturate(180%) !important;
    border: 1px solid rgba(255, 255, 255, 0.4) !important;
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1) !important;
    padding: 10px !important;
    /* overflow: hidden; */
}

.dropdown ul.dropdown-menu:before {
    content: "";
    border-bottom: 10px solid rgba(255, 255, 255, 0.99) !important;
    border-right: 10px solid transparent;
    border-left: 10px solid transparent;
    position: absolute;
    top: -10px;
    right: 16px;
    z-index: 10;
}

.dropdown ul.dropdown-menu:after {
    content: "";
    border-bottom: 12px solid rgba(255, 255, 255, 0.59) !important;
    border-right: 12px solid transparent;
    border-left: 12px solid transparent;
    position: absolute;
    top: -12px;
    right: 14px;
    z-index: 9;
}

.dropdown-menu .dropdown-item {
color: #1a1a1a !important;
font-weight: 500;
padding: 8px 15px;
border-radius: 8px;
}

/* হোভার করলে আইটেমের ব্যাকগ্রাউন্ড */
.dropdown-menu .dropdown-item:hover {
background: rgba(18, 26, 148, 0.5) !important;
color: #000 !important;
}

/* ডিভাইডার লাইন */
.dropdown-divider {
border-top: 1px solid rgba(255, 255, 255, 1) !important;
margin: 5px 0;
}


/* লোগো এবং ইমেজ ইফেক্ট */
.company-logo {
    height: 32px;
    width: auto;
    filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.4));
    transition: transform 0.3s ease;
}

.company-logo:hover {
    transform: scale(1.05);
}

.user-image {
    border: 2px solid rgba(255, 255, 255, 0.2);
    padding: 1px;
}

/* ভাষা সিলেক্টর স্টাইল */
#languageDropdown {
    background: rgba(255, 255, 255, 0.05);
    margin: 0 5px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.app-header .dev-svg{
  max-height: 2.5rem;
}

/* রেসপন্সিভ ফিক্স */


@media (max-width: 767.98px) {
    /* হেডার কন্টেইনার ফিক্স */
    .app-header .container-fluid {
        display: flex;
        flex-wrap: nowrap; /* আইটেমগুলোকে এক লাইনে রাখবে, নিচে নামতে দেবে না */
        padding-left: 5px;
        padding-right: 5px;
        justify-content: space-between;
    }

    /* লোগোর সাইজ কমানো */
    .company-logo {
        height: 24px !important; /* ছোট স্ক্রিনে লোগো ছোট করা */
        margin-top: 0 !important;
    }

    /* ইউজার ইমেজ এবং টেক্সট ফিক্স */
    .user-menu .user-name {
        display: none !important; /* মোবাইলে নাম হাইড রাখা ভালো */
    }

    .user-image {
        width: 28px !important; /* প্রোফাইল ইমেজ ছোট করা */
        height: 28px !important;
        margin-right: 0 !important;
    }

    /* নেভিগেশন আইটেমগুলোর গ্যাপ কমানো */
    .app-header .navbar-nav .nav-link {
        padding: 0.5rem 0.4rem !important;
    }

    /* ড্রপডাউন মেনু যাতে স্ক্রিনের বাইরে না যায় */
    .dropdown-menu-end {
        right: 5px !important;
        left: auto !important;
        position: absolute !important;
    }

    /* ল্যাঙ্গুয়েজ সিলেক্টর মোবাইলে ছোট করা */
    #languageDropdown span:not(.hide-mobile) {
        font-size: 16px !important;
    }
}
</style>


