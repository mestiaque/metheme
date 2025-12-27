@extends('me::blank')
@section('title', 'Login')
@section('meta-title', 'Login - ' . config('app.name'))
@section('content')
    <div class="login-card">
        <div class="login-form">
            <div class="login-header">
                <div class="login-avatar">
                    {{-- <i class="fas fa-user"></i> --}}
                    <img loading="lazy" src="{{ asset('assets/img/favicon/Encodex.ico') }}" class="brand-image opacity-75 shadow " style="width: 100%" alt="mESTIAQUE">
                </div>
                <h1 class="login-title text-shadow">{{ __('WELCOME') }}</h1>
                <p style="text-align: center; color:#ffffff7a" class="text-shadow">{{ __('Enter your username and password to log in.') }}</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success mb-4">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group ">
                    <label class="text-shadow" for="email">{{ __('EMAIL') }}</label>
                    <input type="email" class="form-control text-shadow box-shadow" id="email" name="email"
                        value="{{ old('email') }}" spellcheck="false" required autofocus>
                </div>

                <div class="form-group" style="position: relative;">
                    <label class="text-shadow" for="password">{{ __('PASSWORD') }}</label>
                    <input type="password" class="form-control text-shadow box-shadow" id="password" name="password" required>
                    <span class="password-toggle text-shadow" id="togglePassword" style="position: absolute; right: 15px; top: 41px; cursor: pointer;">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>


                <div class="remember-me">
                    <label class="custom-checkbox text-shadow ">
                        <input type="checkbox" class="box-shadow" id="remember_me" name="remember">
                        <span class="box-shadow"></span>
                        {{ __('Remember Me') }}
                    </label>
                </div>


                <button type="submit" class="btn-login btn btn-blank box-shadow">
                    {{ __('LOGIN') }}
                </button>
            </form>
        </div>
    </div>


    @push('css')
    <style>

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .login-card {
                /* min-height: 300px; */
                /* margin-top: -1rem; */
            }

            .login-image {
                display: none; /* Hide image completely on mobile */
            }

            .login-form {
                padding: 30px 20px;
            }

            .form-control {
                /* width: 87%; */
            }
        }


           .login-card {
            display: flex;
            width: 100%;
            max-width: 1000px;
            /* background-color: var(--light-color); */
            border-radius: 15px;
            overflow: hidden;
            box-shadow:
                0 4px 6px rgba(0, 0, 0, 0.3),  /* subtle close shadow */
                0 10px 20px rgba(0, 0, 0, 0.4), /* deeper shadow */
                0 15px 40px rgba(0, 0, 0, 0.5); /* big, diffused shadow */

        }
        .login-image {
            flex: 1;
            background: url('/assets/img/default-img/login-bg-1.jpeg') center center;
            position: relative;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
        }

        .login-image:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 38, 255, 0.1);
        }

        .login-form {
            flex: 1;
            padding: 40px;
            /* background-color: var(--light-color); */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 5px;
        }

        .login-avatar {
            position: relative; /* for pseudo elements */
            width: 80px;
            height: 80px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;

            /* Stronger 3D inset shadows for depth */
            box-shadow:
                inset 6px 6px 8px rgba(0, 0, 0, 0.15),    /* deep shadow bottom-right */
                inset -6px -6px 8px rgba(255, 255, 255, 0.9), /* bright highlight top-left */
                5px 5px 15px rgba(0, 0, 0, 0.2);           /* pronounced outer shadow */
        }

        /* Glossy highlight */
        .login-avatar::before {
            content: "";
            position: absolute;
            top: 12%;
            left: 12%;
            width: 40%;
            height: 30%;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.85);
            filter: blur(6px);
            pointer-events: none;
        }

        /* Optional subtle shine */
        .login-avatar::after {
            content: "";
            position: absolute;
            bottom: 18%;
            right: 15%;
            width: 30%;
            height: 30%;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            filter: blur(4px);
            pointer-events: none;
            transform: rotate(20deg);
        }



        .login-avatar i {
            font-size: 40px;
            color: white;
        }

        .login-title {
            font-family: cursive;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            color: var(--accent-color);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--accent-color);
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .form-control {
            padding: 12px 15px;
            border: 1px solid #002472;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
            background: none !important;
            color: whitesmoke !important;
        }

        .form-control:focus {
            border-color: var(--focus-color);
            box-shadow: none;
            outline: none;
        }
              .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            color: var(--accent-color);
        }

        .remember-me input {
            margin-right: 8px;
        }

        .password-toggle {
            color: #888;
            user-select: none;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .text-shadow{
            text-shadow: 2px 2px 5px rgba(0,0,0,0.5);
        }
        .box-shadow{
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .custom-checkbox input {
            /* Hide visually but keep focusable */
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
            margin: 0;
            padding: 0;
        }

        .custom-checkbox {
            display: inline-flex;
            align-items: center;
            cursor: pointer;
            user-select: none;
            position: relative;
        }

        /* custom box */
        .custom-checkbox span {
            width: 20px;
            height: 20px;
            display: inline-block;
            background: none;
            border: 1px solid #002472;
            border-radius: 4px;
            margin-right: 8px;
            position: relative;
            transition: background 0.2s, border-color 0.2s, box-shadow 0.2s;
        }

        /* checkmark */
        .custom-checkbox span::after {
            content: "";
            position: absolute;
            display: none;
            left: 7px;
            top: 3px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        /* show checkmark when checked */
        .custom-checkbox input:checked + span::after {
            display: block;
        }

        /* focus effect */
        .custom-checkbox input:focus + span {
            border-color: var(--focus-color);
            outline: none;
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        textarea:-webkit-autofill,
        select:-webkit-autofill {
            -webkit-box-shadow: 0 0 0px 1000px #11111100 inset; /* তোমার bg color */
            -webkit-text-fill-color: white; /* text color */
            transition: background-color 5000s ease-in-out 0s;
        }


    </style>
    @endpush

    @push('js')
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.querySelector('#togglePassword');
            const passwordInput = document.querySelector('#password');

            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle the icon class
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
    </script>
    @endpush
@endsection