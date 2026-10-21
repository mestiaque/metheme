@extends('me::blank')
@section('title', 'Login')
@section('meta-title', 'Login - ' . config('app.name'))
@section('content')
    <div class="login-card glass-card blur-card">
        <div class="login-form">
            <div class="login-header mb-0">
                <div class="login-avatar">
                    {{-- <i class="fas fa-user"></i> --}}
                    @php $logo = (get_setting('app_logo')) ? get_image('app_logo') : asset('assets/img/favicon/Encodex.ico'); @endphp
                    <img loading="lazy" src="{{ $logo }}" class="brand-image opacity-75 " style="width: 100%" alt="M. ESTIAQUE">
                </div>
                <h1 class="login-title text-shadow">{{ __('me::me.WELCOME') }}</h1>
                <p style="text-align: center; color:#6b7280" class="text-shadow">{{ __('me::me.Enter your email/phone and password to login.') }}</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <div class="alert alert-danger mb-4" id="loginErrorBox" @if (! $errors->any()) style="display:none;" @endif>
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <!-- Tab Labels (Clickable) -->
                <div class="form-group" style="position: relative;">
                    <label class="text-shadow" for="user_name">{{ __('me::me.EMAIL / PHONE') }}</label>
                    <input type="text" spellcheck="false" class="form-control text-shadow box-shadow @error('user_name') is-invalid @enderror" name="user_name" id="email" placeholder="{{ __('me::me.EMAIL OR PHONE') }}" value="{{ old('user_name') }}" required>
                </div>

                <div class="form-group" style="position: relative;">
                    <label class="text-shadow" for="password">{{ __('me::me.PASSWORD') }}</label>
                    <input type="password" class="form-control text-shadow box-shadow @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ __('me::me.PASSWORD') }}" value="{{ old('password') }}"  required>
                    <span class="password-toggle text-shadow" id="togglePassword" style="position: absolute; right: 15px; top: 41px; cursor: pointer;">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>


                <div class="remember-me mb-3">
                    <label class="custom-checkbox text-shadow ">
                        <input type="checkbox" class="box-shadow" id="remember_me" name="remember">
                        <span class="box-shadow"></span>
                        {{ __('me::me.Remember Me') }}
                    </label>
                </div>

                <div class="fp-login-btn-wrap">
                    <button type="submit" class="btn-blank fp-login-btn" id="fpLoginBtn">
                        <span class="fp-text">{{ __('me::me.LOGIN') }}</span>

                        <svg class="fp-fingerprint fp-fingerprint-base" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" aria-hidden="true">
                            <g fill="none" stroke-width="2" stroke-linecap="round">
                                <path class="fp-odd" d="m 25.117139,57.142857 c 0,0 -1.968558,-7.660465 -0.643619,-13.149003 1.324939,-5.488538 4.659682,-8.994751 4.659682,-8.994751" />
                                <path class="fp-odd" d="m 31.925369,31.477584 c 0,0 2.153609,-2.934998 9.074971,-5.105078 6.921362,-2.17008 11.799844,-0.618718 11.799844,-0.618718" />
                                <path class="fp-odd" d="m 57.131213,26.814448 c 0,0 5.127709,1.731228 9.899495,7.513009 4.771786,5.781781 4.772971,12.109204 4.772971,12.109204" />
                                <path class="fp-odd" d="m 72.334009,50.76769 0.09597,2.298098 -0.09597,2.386485" />
                                <path class="fp-even" d="m 27.849282,62.75 c 0,0 1.286086,-1.279223 1.25,-4.25 -0.03609,-2.970777 -1.606117,-7.675266 -0.625,-12.75 0.981117,-5.074734 4.5,-9.5 4.5,-9.5" />
                                <path class="fp-even" d="m 36.224282,33.625 c 0,0 8.821171,-7.174484 19.3125,-2.8125 10.491329,4.361984 11.870558,14.952665 11.870558,14.952665" />
                                <path class="fp-even" d="m 68.349282,49.75 c 0,0 0.500124,3.82939 0.5625,5.8125 0.06238,1.98311 -0.1875,5.9375 -0.1875,5.9375" />
                                <path class="fp-odd" d="m 31.099282,65.625 c 0,0 1.764703,-4.224042 2,-7.375 0.235297,-3.150958 -1.943873,-9.276886 0.426777,-15.441942 2.370649,-6.165056 8.073223,-7.933058 8.073223,-7.933058" />
                                <path class="fp-odd" d="m 45.849282,33.625 c 0,0 12.805566,-1.968622 17,9.9375 4.194434,11.906122 1.125,24.0625 1.125,24.0625" />
                                <path class="fp-even" d="m 59.099282,70.25 c 0,0 0.870577,-2.956221 1.1875,-4.5625 0.316923,-1.606279 0.5625,-5.0625 0.5625,-5.0625" />
                                <path class="fp-even" d="m 60.901059,56.286612 c 0,0 0.903689,-9.415996 -3.801777,-14.849112 -3.03125,-3.5 -7.329245,-4.723939 -11.867187,-3.8125 -5.523438,1.109375 -7.570313,5.75 -7.570313,5.75" />
                                <path class="fp-even" d="m 34.072577,68.846248 c 0,0 2.274231,-4.165782 2.839205,-9.033748 0.443558,-3.821814 -0.49394,-5.649939 -0.714206,-8.05386 -0.220265,-2.403922 0.21421,-4.63364 0.21421,-4.63364" />
                                <path class="fp-odd" d="m 37.774165,70.831845 c 0,0 2.692139,-6.147592 3.223034,-11.251208 0.530895,-5.103616 -2.18372,-7.95562 -0.153491,-13.647655 2.030229,-5.692035 8.108442,-4.538898 8.108442,-4.538898" />
                                <path class="fp-odd" d="m 54.391174,71.715729 c 0,0 2.359472,-5.427681 2.519068,-16.175068 0.159595,-10.747388 -4.375223,-12.993087 -4.375223,-12.993087" />
                                <path class="fp-even" d="m 49.474282,73.625 c 0,0 3.730297,-8.451831 3.577665,-16.493718 -0.152632,-8.041887 -0.364805,-11.869326 -4.765165,-11.756282 -4.400364,0.113044 -3.875,4.875 -3.875,4.875" />
                                <path class="fp-even" d="m 41.132922,72.334447 c 0,0 2.49775,-5.267079 3.181981,-8.883029 0.68423,-3.61595 0.353553,-9.413359 0.353553,-9.413359" />
                                <path class="fp-odd" d="m 45.161782,73.75 c 0,0 1.534894,-3.679847 2.40625,-6.53125 0.871356,-2.851403 1.28125,-7.15625 1.28125,-7.15625" />
                                <path class="fp-odd" d="m 48.801947,56.125 c 0,0 0.234502,-1.809418 0.109835,-3.375 -0.124667,-1.565582 -0.5625,-3.1875 -0.5625,-3.1875" />
                            </g>
                        </svg>

                        <svg class="fp-fingerprint fp-fingerprint-active" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" aria-hidden="true">
                            <g fill="none" stroke-width="2" stroke-linecap="round">
                                <path class="fp-odd" d="m 25.117139,57.142857 c 0,0 -1.968558,-7.660465 -0.643619,-13.149003 1.324939,-5.488538 4.659682,-8.994751 4.659682,-8.994751" />
                                <path class="fp-odd" d="m 31.925369,31.477584 c 0,0 2.153609,-2.934998 9.074971,-5.105078 6.921362,-2.17008 11.799844,-0.618718 11.799844,-0.618718" />
                                <path class="fp-odd" d="m 57.131213,26.814448 c 0,0 5.127709,1.731228 9.899495,7.513009 4.771786,5.781781 4.772971,12.109204 4.772971,12.109204" />
                                <path class="fp-odd" d="m 72.334009,50.76769 0.09597,2.298098 -0.09597,2.386485" />
                                <path class="fp-even" d="m 27.849282,62.75 c 0,0 1.286086,-1.279223 1.25,-4.25 -0.03609,-2.970777 -1.606117,-7.675266 -0.625,-12.75 0.981117,-5.074734 4.5,-9.5 4.5,-9.5" />
                                <path class="fp-even" d="m 36.224282,33.625 c 0,0 8.821171,-7.174484 19.3125,-2.8125 10.491329,4.361984 11.870558,14.952665 11.870558,14.952665" />
                                <path class="fp-even" d="m 68.349282,49.75 c 0,0 0.500124,3.82939 0.5625,5.8125 0.06238,1.98311 -0.1875,5.9375 -0.1875,5.9375" />
                                <path class="fp-odd" d="m 31.099282,65.625 c 0,0 1.764703,-4.224042 2,-7.375 0.235297,-3.150958 -1.943873,-9.276886 0.426777,-15.441942 2.370649,-6.165056 8.073223,-7.933058 8.073223,-7.933058" />
                                <path class="fp-odd" d="m 45.849282,33.625 c 0,0 12.805566,-1.968622 17,9.9375 4.194434,11.906122 1.125,24.0625 1.125,24.0625" />
                                <path class="fp-even" d="m 59.099282,70.25 c 0,0 0.870577,-2.956221 1.1875,-4.5625 0.316923,-1.606279 0.5625,-5.0625 0.5625,-5.0625" />
                                <path class="fp-even" d="m 60.901059,56.286612 c 0,0 0.903689,-9.415996 -3.801777,-14.849112 -3.03125,-3.5 -7.329245,-4.723939 -11.867187,-3.8125 -5.523438,1.109375 -7.570313,5.75 -7.570313,5.75" />
                                <path class="fp-even" d="m 34.072577,68.846248 c 0,0 2.274231,-4.165782 2.839205,-9.033748 0.443558,-3.821814 -0.49394,-5.649939 -0.714206,-8.05386 -0.220265,-2.403922 0.21421,-4.63364 0.21421,-4.63364" />
                                <path class="fp-odd" d="m 37.774165,70.831845 c 0,0 2.692139,-6.147592 3.223034,-11.251208 0.530895,-5.103616 -2.18372,-7.95562 -0.153491,-13.647655 2.030229,-5.692035 8.108442,-4.538898 8.108442,-4.538898" />
                                <path class="fp-odd" d="m 54.391174,71.715729 c 0,0 2.359472,-5.427681 2.519068,-16.175068 0.159595,-10.747388 -4.375223,-12.993087 -4.375223,-12.993087" />
                                <path class="fp-even" d="m 49.474282,73.625 c 0,0 3.730297,-8.451831 3.577665,-16.493718 -0.152632,-8.041887 -0.364805,-11.869326 -4.765165,-11.756282 -4.400364,0.113044 -3.875,4.875 -3.875,4.875" />
                                <path class="fp-even" d="m 41.132922,72.334447 c 0,0 2.49775,-5.267079 3.181981,-8.883029 0.68423,-3.61595 0.353553,-9.413359 0.353553,-9.413359" />
                                <path class="fp-odd" d="m 45.161782,73.75 c 0,0 1.534894,-3.679847 2.40625,-6.53125 0.871356,-2.851403 1.28125,-7.15625 1.28125,-7.15625" />
                                <path class="fp-odd" d="m 48.801947,56.125 c 0,0 0.234502,-1.809418 0.109835,-3.375 -0.124667,-1.565582 -0.5625,-3.1875 -0.5625,-3.1875" />
                            </g>
                        </svg>
                    </button>
                </div>


                <div class="mt-3 text-center">
                    @if(get_setting('enable_registration'))
                    <!-- রেজিস্ট্রেশন লিঙ্ক (যদি নতুন ইউজার হয়) -->
                    <p style="color: rgba(15, 45, 74, 0.7); font-size: 0.9rem;" class="mb-2">
                        {{ __("me::me.Don't have an account?") }}
                        <a href="{{ route('register') }}" style="color: #0f2d4a; font-weight: 600; text-decoration: none; border-bottom: 1px solid #0f2d4a;">
                            {{ __('me::me.Create Account') }}
                        </a>
                    </p>
                    @endif

                    @if(get_setting('enable_forget_password'))
                    <!-- পাসওয়ার্ড ভুলে গেলে -->
                    <p class="mt-2">
                        <a href="{{ route('password.forget') }}" style="color: rgba(15, 45, 74, 0.75); font-size: 0.85rem; text-decoration: none; transition: 0.3s;" onmouseover="this.style.color='#0f2d4a'" onmouseout="this.style.color='rgba(15, 45, 74, 0.75)'">
                            <i class="fas fa-lock me-1"></i> {{ __('me::me.Forgot your password?') }}
                        </a>
                    </p>
                    @endif
                </div>
                <div class="auth-footer mt-4 text-center d-none">
                    <div class="d-flex justify-content-between align-items-center" style="max-width: 300px; margin: 0 auto; border-top: 1px solid rgba(15,45,74,0.1); pt-3">

                        <!-- Registration/Login Toggle -->
                        <a href="{{ route('register') }}" class="text-decoration-none" style="color: #0f2d4ad1; font-size: 13px;">
                            {{ __('me::me.Register') }}
                        </a>

                        <span style="color: rgba(15,45,74,0.2)">|</span>


                        <!-- Forgot Password -->
                        <a href="{{ route('password.forget') }}" class="text-decoration-none" style="color: #0f2d4ad1; font-size: 13px;">
                            {{ __('me::me.Reset Password') }}
                        </a>


                    </div>
                </div>


            </form>
        </div>
    </div>


    @include("me::auth.glass")
    @include("me::auth.css")
    @include("me::auth.shared")

    {{-- শুধু এই লগইন পেজেই ব্যবহৃত - fingerprint-scan স্টাইল login বাটন, তাই অন্য কোনো
         পেজে না দেখিয়ে এখানেই push করা হচ্ছে, blank.blade.php/css.blade.php তে না --}}
    @push('css')
    <style>
        /* .btn-blank এর নিজস্ব হোভার-ব্লব চেহারা (border/transparent bg/হোভারে ব্লব ভরাট)
           অপরিবর্তিত থাকবে - এখানে শুধু ক্লিক করলে (scanning/success/denied state)
           কী বদলাবে তা যোগ করা হচ্ছে, idle/hover অবস্থা আগের মতোই থাকবে */
        /* margin:auto দিয়ে সেন্টার করলে width ট্রানজিশনের সময় সেটা মসৃণভাবে
           interpolate হয় না (শুধু শেষে এসে স্ন্যাপ করে, পুরো অ্যানিমেশন জুড়ে left
           থেকেই সংকুচিত হতে দেখা যায়) - তাই প্যারেন্টকে flex+justify-content:center
           করে centering-টা parent-এর layout-এর উপর ছেড়ে দেওয়া হচ্ছে, যেটা প্রতি
           ফ্রেমেই সঠিকভাবে recalculate হয় */
        .fp-login-btn-wrap {
            display: flex;
            justify-content: center;
        }

        .fp-login-btn {
            width: 100%;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: width 0.5s cubic-bezier(.4,0,.2,1), background-color 0.3s, color 0.3s, box-shadow 0.3s;
        }

        .fp-login-btn.scanning,
        .fp-login-btn.success,
        .fp-login-btn.denied {
            width: 56px;
            padding: 0;
        }

        .fp-login-btn.scanning { background-color: var(--blob-color); }
        .fp-login-btn.success { background-color: #1fb466; }
        .fp-login-btn.denied { background-color: #d93b3b; }

        .fp-login-btn.scanning::before,
        .fp-login-btn.success::before,
        .fp-login-btn.denied::before {
            opacity: 0; /* সলিড বৃত্তে বর্ডার আউটলাইনের দরকার নেই */
        }

        .fp-login-btn.scanning .btn-blank__inner,
        .fp-login-btn.success .btn-blank__inner,
        .fp-login-btn.denied .btn-blank__inner {
            opacity: 0; /* ব্লব লেয়ার লুকানো, ফিঙ্গারপ্রিন্ট/চেকমার্ক দেখাবে */
        }

        .fp-login-btn .fp-text {
            transition: opacity 0.25s, transform 0.25s;
        }

        .fp-login-btn.scanning .fp-text,
        .fp-login-btn.success .fp-text,
        .fp-login-btn.denied .fp-text {
            opacity: 0;
            transform: scale(0.6);
        }

        .fp-login-btn .fp-fingerprint {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 44px;
            height: 44px;
            transform: translate(-50%, -50%);
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }

        .fp-login-btn.scanning .fp-fingerprint,
        .fp-login-btn.success .fp-fingerprint,
        .fp-login-btn.denied .fp-fingerprint {
            opacity: 1; /* success/denied এও fingerprint-ই থাকবে, শুধু বাটনের রং বদলাবে */
        }

        .fp-login-btn .fp-fingerprint-base { stroke: rgba(255, 255, 255, 0.45); }
        .fp-login-btn .fp-fingerprint-active { stroke: #ffffff; }

        /* বিশ্রাম অবস্থা - আনড্রন */
        .fp-login-btn .fp-fingerprint-active .fp-odd {
            stroke-dasharray: 0px 50px;
            stroke-dashoffset: 1px;
        }
        .fp-login-btn .fp-fingerprint-active .fp-even {
            stroke-dasharray: 50px 50px;
            stroke-dashoffset: -41px;
        }

        /* স্ক্যান শুরু - একবার ড্র-ইন হবে, success/denied এও আঁকা অবস্থাতেই থাকবে
           (শুধু রং বদলাবে, fingerprint আবার আনড্রন হয়ে যাবে না) */
        .fp-login-btn.scanning .fp-fingerprint-active .fp-odd,
        .fp-login-btn.success .fp-fingerprint-active .fp-odd,
        .fp-login-btn.denied .fp-fingerprint-active .fp-odd {
            stroke-dasharray: 50px 50px;
            transition: stroke-dasharray 700ms 100ms;
        }
        .fp-login-btn.scanning .fp-fingerprint-active .fp-even,
        .fp-login-btn.success .fp-fingerprint-active .fp-even,
        .fp-login-btn.denied .fp-fingerprint-active .fp-even {
            stroke-dashoffset: 0px;
            transition: stroke-dashoffset 700ms;
        }

        /* ড্র-ইন শেষ হওয়ার পর সার্ভারের উত্তরের অপেক্ষায় আলতো পালস্ - লুপ করে চলবে */
        .fp-login-btn.scanning .fp-fingerprint-active {
            animation: fp-pulse 1.1s ease-in-out 900ms infinite;
        }

        @keyframes fp-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.35; }
        }

        .fp-login-btn.denied {
            animation: fp-shake 0.45s;
        }

        @keyframes fp-shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-6px); }
            40% { transform: translateX(6px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
        }
    </style>
    @endpush

    @push('js')
    <script>
    (function () {
        var form = document.getElementById('loginForm');
        var btn = document.getElementById('fpLoginBtn');
        if (!form || !btn) return;

        var loginUrl = "{{ route('login') }}";

        // fingerprint draw-in + pulse অ্যানিমেশন দেখতে ~800-900ms লাগে, কিন্তু আসল
        // সার্ভার রেসপন্স তার চেয়ে অনেক দ্রুত (localhost এ প্রায়ই ১০০-২০০ms) আসতে পারে -
        // তখন scanning সাথে সাথে সরিয়ে ফেললে fingerprint দেখারই সুযোগ পাবে না।
        // তাই scanning state-এর একটা ন্যূনতম সময় বেঁধে দেওয়া হচ্ছে, যত দ্রুতই উত্তর আসুক না কেন।
        var MIN_SCAN_MS = 1100;

        form.addEventListener('submit', function (e) {
            if (btn.classList.contains('scanning')) { e.preventDefault(); return; } // ডাবল-সাবমিট আটকানো
            e.preventDefault();

            btn.classList.remove('success', 'denied');
            btn.classList.add('scanning');
            var scanStarted = Date.now();

            function afterMinScan(callback) {
                var remaining = MIN_SCAN_MS - (Date.now() - scanStarted);
                setTimeout(callback, Math.max(remaining, 0));
            }

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                credentials: 'same-origin'
            }).then(function (res) {
                // POST-এর পর ফাইনাল URL যদি আবার login পেজেই ফিরে আসে, মানে auth fail
                // হয়ে Laravel back()->withErrors() করেছে - এভাবে কন্ট্রোলার না ছুঁয়েই বোঝা যায়
                var failed = res.url === loginUrl || res.url === loginUrl + '/';

                if (failed) {
                    // fetch নিজেই একবার redirect follow করে ফেলেছে, মানে Laravel এর
                    // one-time session-flashed error ততক্ষণে "ব্যবহৃত" হয়ে গেছে -
                    // তাই আবার window.location দিয়ে নেভিগেট করলে error দেখাই যেত না।
                    // এখানে fetch যে HTML ইতিমধ্যে পেয়েছে সেটা থেকেই error বের করা হচ্ছে
                    return res.text().then(function (html) {
                        return { failed: true, html: html };
                    });
                }
                return { failed: false, url: res.url };
            }).then(function (result) {
                afterMinScan(function () {
                    btn.classList.remove('scanning');

                    if (result.failed) {
                        btn.classList.add('denied');

                        try {
                            var doc = new DOMParser().parseFromString(result.html, 'text/html');
                            var fetchedBox = doc.getElementById('loginErrorBox');
                            var message = fetchedBox ? fetchedBox.textContent.replace(/\s+/g, ' ').trim() : '';
                            if (message && typeof toastr !== 'undefined') {
                                toastr.error(message); // অ্যাডমিন প্যানেলের মতোই top-right toastr
                            }
                        } catch (err) {}

                        setTimeout(function () {
                            btn.classList.remove('denied');
                        }, 700);
                    } else {
                        btn.classList.add('success');
                        setTimeout(function () {
                            window.location.href = result.url;
                        }, 900);
                    }
                });
            }).catch(function () {
                afterMinScan(function () {
                    btn.classList.remove('scanning');
                    btn.classList.add('denied');
                    setTimeout(function () {
                        btn.classList.remove('denied');
                    }, 700);
                });
            });
        });
    })();
    </script>
    @endpush
@endsection
