<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body sticky-top shadow guest-header">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Start Navbar Links-->
    <ul class="navbar-nav">
      <li class="nav-item d-nonex d-md-block">
        <a href="/" class="nav-link">
          <img  loading="lazy" src="{{ get_image('app_logo') ?? asset('assets/img/default-img/Encodex_c.png') }}" class="company-logo" alt="Company Logo">
        </a>
      </li>
    </ul>
    <!--end::Start Navbar Links-->

    <!--begin::End Navbar Links-->
    <ul class="navbar-nav ms-auto">

      <!--end::Navbar Search-->

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

        @php
            $animals = [
                ['name' => 'Happy Panda', 'emoji' => '🐼'],
                ['name' => 'Sneaky Fox', 'emoji' => '🦊'],
                ['name' => 'Witty Owl', 'emoji' => '🦉'],
                ['name' => 'Cheeky Monkey', 'emoji' => '🐒'],
                ['name' => 'Bouncy Bunny', 'emoji' => '🐇'],
                ['name' => 'Curious Cat', 'emoji' => '🐱'],
                ['name' => 'Sly Raccoon', 'emoji' => '🦝'],
                ['name' => 'Jolly Dolphin', 'emoji' => '🐬'],
                ['name' => 'Mischief Mouse', 'emoji' => '🐭'],
                ['name' => 'Playful Penguin', 'emoji' => '🐧'],
                ['name' => 'Chirpy Sparrow', 'emoji' => '🐦'],
                ['name' => 'Brave Bear', 'emoji' => '🐻'],
                ['name' => 'Daring Duck', 'emoji' => '🦆'],
                ['name' => 'Funky Frog', 'emoji' => '🐸'],
            ];

            // Pick random if session not set
            $animal = session('user_animal') ?? $animals[array_rand($animals)];

            // Save to session so it stays the same for user
            if (!session()->has('user_animal')) {
                session(['user_animal' => $animal]);
            }
        @endphp


      <li class="nav-item dropdown user-menu d-inline-flex" >
          <span class="" style="font-size:25px;font-family: cursive; height: 1rem; width: 1rem; margin-right:2px">{{ $animal['emoji'] }}</span>
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" style="vertical-align: middle !important">
                    {{-- <img class="user-image rounded-circle shadow" src="{{ asset('front/img/smile_face.jpg') }}"> --}}
                <span class="d-none d-md-inline user-name">{{ session('user_name') ?? $animal['name'] }}</span>
            </a>
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
        margin-top: -6.5px;
        /* Stronger, deeper white drop shadow */
        filter:
            drop-shadow(0 0 8px white)
            drop-shadow(0 0 12px white)
            drop-shadow(0 0 16px white);
        padding: 2px;
        border-radius: 6px;
    }

    .guest-header{
        background: #2c7ac6 !important;
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



    @media (max-width: 767px) {
        .hide-mobile {
            display: none !important;
        }
        .container-fluid{
            padding: 0  ;
        }
    }


</style>



