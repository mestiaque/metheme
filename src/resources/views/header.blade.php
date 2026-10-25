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
        <a href="{{ me_home_url() }}"
            class="nav-link d-flex align-items-center"
            style="height: 40px; padding:0px !important;"> <!-- প্যাডিং ০ করে দেওয়া হয়েছে যাতে ইমেজ বড় হলে সমস্যা না হয় -->
            {{-- Configurations → Header logo: shown only when switched on and an image is uploaded; otherwise empty --}}
            @if(get_setting('show_header_logo', true) && ($headerLogo = get_image('header_logo')))
                <img src="{{ $headerLogo }}" alt="{{ get_setting('app_name', config('app.name')) }}" style="max-height: 2.5rem; width: auto; display: block;">
            @endif
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
          <span class="navbar-badge badge bg-danger-subtle text-danger">3</span>
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
              <input type="text" class="search-input" id="navSearchInput" placeholder="@lang('me::me.search_menu_placeholder')" aria-label="@lang('me::me.Search')">
              <div id="navSearchResults" class="menu-search-results" style="display:none;"></div>
          </div>
      </li>
      <!--end::search Dropdown Menu-->

      <!--begin::Notifications Dropdown Menu-->
      <li class="nav-item dropdown d-none">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="bi bi-bell-fill"></i>
          <span class="navbar-badge badge bg-warning-subtle text-warning">15</span>
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
                @if(Auth::user()->avatar_url)
                    <img class="user-image rounded-circle shadow"
                        src="{{ Auth::user()->avatar_url }}">
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
            <a class="dropdown-item" href="{{ Route::has(me_prefix().'.profile.edit') ? route(me_prefix().'.profile.edit') : route('profile.edit') }}">
              <i class="bi bi-person me-2"></i> @lang("me::me.Profile")
            </a>
          </li>
          @can('setting.edit')
                    @if(get_setting('show_settings_link', true))
          <li class="py-1">
            <a class="dropdown-item" href="{{ Route::has(me_prefix().'.settings.edit') ? route(me_prefix().'.settings.edit') : route('settings.edit') }}">
              <i class="bi bi-gear me-2"></i> @lang("me::me.Settings")
            </a>
          </li>
                    @endif
          @endcan
          <li><hr class="dropdown-divider"></li>
          <li class="pb-1">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="dropdown-item">
                <i class="bi bi-box-arrow-right me-2"></i> @lang("me::me.Sign Out")
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
            empty.textContent = @json(__('me::me.no_matching_menu_found'));
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


