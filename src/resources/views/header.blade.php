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


      <!--end::search Dropdown Menu-->
      <li class="nav-item search-box">
          <div class="search-wrapper" id="navSearchWrapper">
              <button type="button" class="nav-linkx search-icon-btn" id="navSearchToggle" aria-label="Search" aria-expanded="false">
                  <i class="bi bi-search"></i>
              </button>
              <input type="text" class="search-input" id="navSearchInput" placeholder="Search..." aria-label="Search">
              <div id="navSearchResults" class="menu-search-results" style="display:none;"></div>
          </div>
      </li>
      <!--end::search Dropdown Menu-->

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
        <a href="#" class="nav-link dropdown-toggle" id="userMenuToggle" aria-expanded="false">
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
        <ul class="dropdown-menu" id="userMenuDropdown" style="display:none;">
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

/* প্রোফাইল ড্রপডাউন - সার্চ রেজাল্টের মতো লিকুইড গ্লাসমরফিজম, একই fixed পজিশনিং কৌশলে
   (body তে পোর্টাল করা হয় জেএস দিয়ে - #userMenuDropdown এখন .user-menu এর ভেতরে থাকবে না,
   তাই নেস্টেড সিলেক্টরের বদলে আইডি সিলেক্টর ব্যবহার হচ্ছে) */
#userMenuDropdown.dropdown-menu {
    position: fixed;
    width: 220px;
    background: rgba(255, 255, 255, 0.05) !important;
    backdrop-filter: blur(15px) saturate(160%) !important;
    -webkit-backdrop-filter: blur(15px) saturate(160%) !important;
    border: 1px solid rgba(255, 255, 255, 0.6) !important;
    border-radius: 16px !important;
    box-shadow: 0 8px 32px rgba(15, 45, 74, 0.18), inset 0 1px 0 rgba(255, 255, 255, 0.7) !important;
    z-index: 2000;
    margin: 0 !important;
}

#userMenuDropdown:before {
    content: "";
    position: absolute;
    top: -7px;
    right: 26px;
    width: 12px;
    height: 12px;
    background: rgba(255, 255, 255, 0.05);
    border-top: 1px solid rgba(255, 255, 255, 0.6);
    border-left: 1px solid rgba(255, 255, 255, 0.6);
    border-radius: 3px 0 0 0;
    transform: rotate(45deg);
    backdrop-filter: blur(15px) saturate(160%);
    -webkit-backdrop-filter: blur(15px) saturate(160%);
}

#userMenuDropdown .dropdown-item {
    color: #0f2d4a !important;
    border-radius: 10px;
    transition: background 0.15s ease, transform 0.15s ease;
}

#userMenuDropdown .dropdown-item:hover {
    background: rgba(15, 155, 214, 0.18) !important;
    color: #0f2d4a !important;
    transform: translateX(2px);
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

.search-box{
  margin-right: 10px;
}

/* এক্সপ্যান্ডেবল সার্চ বক্স */
.search-box .search-wrapper {
    position: relative;
    height: 40px;
    display: flex;
    align-items: center;
}

.search-box .search-icon-btn {
    position: relative;
    z-index: 2;
    height: 40px;
    width: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    padding: 0 !important;
}

.search-box .search-input {
    position: absolute;
    top: 0;
    right: 0;
    height: 40px;
    width: 40px;
    padding: 0 44px 0 14px;
    outline: none;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.55);
    backdrop-filter: blur(22px) saturate(180%);
    -webkit-backdrop-filter: blur(22px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.6);
    box-shadow: 0 4px 18px rgba(15, 45, 74, 0.14), inset 0 1px 0 rgba(255, 255, 255, 0.7);
    color: #0f2d4a;
    font-size: 0.85rem;
    opacity: 0;
    pointer-events: none;
    transition: width 0.35s ease, opacity 0.25s ease;
}

.search-box .search-input::placeholder {
    color: #4b5a6b;
}

.search-box .search-wrapper:hover .search-input,
.search-box .search-wrapper:focus-within .search-input,
.search-box .search-wrapper.active .search-input,
.search-box .search-wrapper.has-results .search-input {
    width: 220px;
    opacity: 1;
    pointer-events: auto;
}

.search-box .search-wrapper:hover .search-icon-btn,
.search-box .search-wrapper:focus-within .search-icon-btn,
.search-box .search-wrapper.active .search-icon-btn,
.search-box .search-wrapper.has-results .search-icon-btn {
    color: #0f9bd6 !important;
}

/* টাইপ করার সময় আইকন অ্যানিমেশন - ম্যাগনিফায়ার ছোট বৃত্তাকার পথে ঘুরবে (সোজা থেকে) */
@keyframes searchIconOrbit {
    from { transform: rotate(0deg) translateX(2.5px) rotate(0deg); }
    to   { transform: rotate(360deg) translateX(2.5px) rotate(-360deg); }
}

.search-box .search-wrapper.typing .search-icon-btn i {
    display: inline-block;
    animation: searchIconOrbit 0.9s linear infinite;
    color: #0f9bd6;
}

@media (max-width: 767.98px) {
    .search-box .search-wrapper:hover .search-input,
    .search-box .search-wrapper.active .search-input,
    .search-box .search-wrapper.has-results .search-input {
        width: 160px;
    }
}

/* সার্চ রেজাল্ট ড্রপডাউন - লিকুইড গ্লাসমরফিজম
   (body তে পোর্টাল করা হয় জেএস দিয়ে, তাই .search-box এর ভেতরে নেস্ট করা সিলেক্টর ব্যবহার হয়নি -
   হেডারের নিজস্ব backdrop-filter একটি নতুন backdrop root তৈরি করে, তাই ভেতরে থাকলে
   হেডারের নিচের পেজ কনটেন্ট ব্লার করতে পারত না) */
.menu-search-results {
    position: fixed;
    width: 220px;
    max-height: 320px;
    overflow-y: auto;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(15px) saturate(160%);
    -webkit-backdrop-filter: blur(15px) saturate(160%);
    border: 1px solid rgba(255, 255, 255, 0.6);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(15, 45, 74, 0.18), inset 0 1px 0 rgba(255, 255, 255, 0.7);
    padding: 8px;
    z-index: 2000;
}

.menu-search-results:before {
    content: "";
    position: absolute;
    top: -7px;
    right: 14px;
    width: 12px;
    height: 12px;
    background: rgba(255, 255, 255, 0.05);
    border-top: 1px solid rgba(255, 255, 255, 0.6);
    border-left: 1px solid rgba(255, 255, 255, 0.6);
    border-radius: 3px 0 0 0;
    transform: rotate(45deg);
    backdrop-filter: blur(15px) saturate(160%);
    -webkit-backdrop-filter: blur(15px) saturate(160%);
}

.menu-search-item {
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-radius: 10px;
    color: #0f2d4a;
    text-decoration: none;
    font-size: 0.85rem;
    transition: background 0.15s ease, transform 0.15s ease;
}

.menu-search-item:hover,
.menu-search-item.active {
    background: rgba(15, 155, 214, 0.18);
    color: #0f2d4a;
    transform: translateX(2px);
}

.menu-search-icon {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: rgba(15, 155, 214, 0.15);
    color: #0f9bd6;
    flex-shrink: 0;
}

.menu-search-text {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.menu-search-title {
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.menu-search-breadcrumb {
    font-size: 0.72rem;
    color: #4b5a6b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.menu-search-empty {
    padding: 10px;
    color: #4b5a6b;
    font-size: 0.8rem;
    text-align: center;
}

@media (max-width: 767.98px) {
    .menu-search-results,
    #userMenuDropdown.dropdown-menu {
        width: 160px;
    }
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

<script>
(function () {
    var wrapper = document.getElementById('navSearchWrapper');
    var toggleBtn = document.getElementById('navSearchToggle');
    var input = document.getElementById('navSearchInput');
    var results = document.getElementById('navSearchResults');
    if (!wrapper || !toggleBtn || !input || !results) return;

    var searchUrl = '{{ route('nav.menuSearch') }}';
    var debounceTimer;
    var activeIndex = -1;

    // হেডারের নিজস্ব backdrop-filter একটি নতুন backdrop root তৈরি করে, তাই রেজাল্ট
    // প্যানেলটি হেডারের ভেতরে থাকলে হেডারের নিচের পেজ কনটেন্ট ব্লার করতে পারত না -
    // body তে সরিয়ে fixed পজিশনে বসানো হচ্ছে যাতে আসল পেজ ব্লার হয়
    document.body.appendChild(results);

    function positionResults() {
        var rect = wrapper.getBoundingClientRect();
        results.style.top = (rect.bottom + 10) + 'px';
        results.style.right = (window.innerWidth - rect.right) + 'px';
    }

    window.addEventListener('resize', function () {
        if (results.style.display === 'block') positionResults();
    });

    function hideResults() {
        results.style.display = 'none';
        results.innerHTML = '';
        activeIndex = -1;
        wrapper.classList.remove('has-results');
    }

    function closeSearch() {
        wrapper.classList.remove('active');
        toggleBtn.setAttribute('aria-expanded', 'false');
        hideResults();
    }

    function renderResults(items) {
        results.innerHTML = '';
        activeIndex = -1;
        wrapper.classList.add('has-results');
        positionResults();

        if (!items.length) {
            var empty = document.createElement('div');
            empty.className = 'menu-search-empty';
            empty.textContent = 'No matching menu found.';
            results.appendChild(empty);
            results.style.display = 'block';
            return;
        }

        items.forEach(function (item) {
            var a = document.createElement('a');
            a.className = 'menu-search-item';
            a.href = item.route;

            var icon = document.createElement('span');
            icon.className = 'menu-search-icon';
            icon.innerHTML = '<i class="' + item.icon + '"></i>';

            var text = document.createElement('span');
            text.className = 'menu-search-text';

            var title = document.createElement('span');
            title.className = 'menu-search-title';
            title.textContent = item.title;
            text.appendChild(title);

            if (item.breadcrumb) {
                var breadcrumb = document.createElement('span');
                breadcrumb.className = 'menu-search-breadcrumb';
                breadcrumb.textContent = item.breadcrumb;
                text.appendChild(breadcrumb);
            }

            a.appendChild(icon);
            a.appendChild(text);
            results.appendChild(a);
        });

        results.style.display = 'block';
    }

    function setActiveItem(items) {
        items.forEach(function (el) { el.classList.remove('active'); });
        var el = items[activeIndex];
        el.classList.add('active');
        el.scrollIntoView({ block: 'nearest' });
    }

    toggleBtn.addEventListener('click', function (e) {
        e.preventDefault();
        var isActive = wrapper.classList.toggle('active');
        toggleBtn.setAttribute('aria-expanded', isActive ? 'true' : 'false');
        if (isActive) {
            // iOS Safari-এর মতো ব্রাউজার শুধু তখনই কিবোর্ড তোলে যখন focus() ক্লিক
            // হ্যান্ডলারের ভেতরেই সিঙ্ক্রোনাসলি কল হয় - rAF/setTimeout দিয়ে দেরি
            // করলে সেটা আর "ইউজার জেসচার" হিসেবে গণ্য হয় না, তাই আগে সিঙ্ক্রোনাস
            // কল, তারপর ট্রানজিশন-টাইমিং সেফটির জন্য এক ফ্রেম পর আবার ফোকাস
            input.focus();
            requestAnimationFrame(function () {
                input.focus();
            });
        } else {
            hideResults();
        }
    });

    document.addEventListener('click', function (e) {
        if (!wrapper.contains(e.target) && !results.contains(e.target)) closeSearch();
    });

    var typingTimer;
    input.addEventListener('input', function () {
        wrapper.classList.add('typing');
        clearTimeout(typingTimer);
        typingTimer = setTimeout(function () {
            wrapper.classList.remove('typing');
        }, 500);

        var term = input.value.trim();
        clearTimeout(debounceTimer);

        if (term.length < 1) {
            hideResults();
            return;
        }

        debounceTimer = setTimeout(function () {
            fetch(searchUrl + '?q=' + encodeURIComponent(term))
                .then(function (res) { return res.json(); })
                .then(renderResults)
                .catch(hideResults);
        }, 250);
    });

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeSearch();
            input.blur();
            return;
        }

        var items = Array.prototype.slice.call(results.querySelectorAll('.menu-search-item'));
        if (!items.length) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeIndex = Math.min(activeIndex + 1, items.length - 1);
            setActiveItem(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            activeIndex = Math.max(activeIndex - 1, 0);
            setActiveItem(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            var target = activeIndex >= 0 ? items[activeIndex] : items[0];
            window.location.href = target.getAttribute('href');
        }
    });
})();
</script>

<script>
(function () {
    var toggle = document.getElementById('userMenuToggle');
    var menu = document.getElementById('userMenuDropdown');
    if (!toggle || !menu) return;

    // সার্চ রেজাল্টের মতোই - হেডারের backdrop-filter একটি নতুন backdrop root
    // তৈরি করে, তাই এই ড্রপডাউনও body তে পোর্টাল করে fixed পজিশনে বসানো হচ্ছে
    document.body.appendChild(menu);

    function positionMenu() {
        var rect = toggle.getBoundingClientRect();
        menu.style.top = (rect.bottom + 10) + 'px';
        menu.style.right = (window.innerWidth - rect.right - 12) + 'px';
    }

    function openMenu() {
        positionMenu();
        menu.style.display = 'block';
        toggle.setAttribute('aria-expanded', 'true');
    }

    function closeMenu() {
        menu.style.display = 'none';
        toggle.setAttribute('aria-expanded', 'false');
    }

    toggle.addEventListener('click', function (e) {
        e.preventDefault();
        if (menu.style.display === 'block') {
            closeMenu();
        } else {
            openMenu();
        }
    });

    document.addEventListener('click', function (e) {
        if (!toggle.contains(e.target) && !menu.contains(e.target)) closeMenu();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && menu.style.display === 'block') closeMenu();
    });

    window.addEventListener('resize', function () {
        if (menu.style.display === 'block') positionMenu();
    });
})();
</script>


