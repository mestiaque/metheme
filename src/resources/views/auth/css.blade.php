@push('css')
<style>
    /* লাইট থিম এখন blank.blade.php নিজেই (:root ভ্যারিয়েবল, body background, .btn-blank সহ)
       ডিফল্ট হিসেবে সেট করে, তাই এখানে আলাদা করে ওভাররাইড করার দরকার নেই */

    /* 1. Base Layout & Card */
    .login-cardX {
        display: flex;
        width: 100%;
        max-width: 1000px;
        border-radius: 15px;
        margin: 0 auto;
        overflow: hidden;
        height: max-content;
        /* background-color: var(--primary-bg); */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3),
                    0 10px 20px var(--shadow-color),
                    0 15px 40px rgba(0, 0, 0, 0.5);
    }

    .login-image {
        flex: 1;
        background: url('/assets/img/default-img/login-bg-1.jpeg') center center / cover;
        position: relative;
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-image:before {
        content: '';
        position: absolute;
        inset: 0;
        background-color: rgba(0, 38, 255, 0.1);
    }

    .login-form {
        flex: 1;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* 2. Header & Avatar Section */
    .login-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 20px;
    }

    .login-avatar {
        position: relative;
        width: 100px;
        height: 100px;
        /* background-color: white; */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        /* box-shadow: inset 6px 6px 8px rgba(0, 0, 0, 0.15),
                    inset -6px -6px 8px rgba(255, 255, 255, 0.9),
                    5px 5px 15px rgba(0, 0, 0, 0.2); */
        border: 2px solid #cbefff77;
        background: rgba(15, 154, 214, 0);
        backdrop-filter: blur(15px) saturate(160%);
        -webkit-backdrop-filter: blur(15px) saturate(160%);
    }

    .login-avatar::before {
        content: "";
        position: absolute;
        top: 12%; left: 12%; width: 40%; height: 30%;
        /* background: rgba(255, 255, 255, 0.85); */
        filter: blur(6px);
        border-radius: 50%;
    }

    .login-title {
        font-family: 'cursive', sans-serif;
        font-size: 24px;
        font-weight: bold;
        color: var(--accent-color);
        margin-bottom: 5px;
    }

    /* 3. Form Controls & Placeholder */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--accent-color);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 16px;
        background: rgba(255, 255, 255, 0.7) !important;
        color: #0f2d4a !important;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--focus-color);
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
        outline: none;
    }

    /* Placeholder Color */
    .form-control::placeholder {
        color: var(--text-muted);
        opacity: 1;
    }


    /* 3. Autofill fix */
    .form-control:-webkit-autofill,
    .form-control:-webkit-autofill:hover,
    .form-control:-webkit-autofill:focus,
    .form-control:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 1000px #ffffff inset !important; /* keeps bg light */
        -webkit-text-fill-color: #0f2d4a !important; /* makes text dark navy */
        transition: background-color 5000s ease-in-out 0s !important;
    }

    /* 4. Password Toggle & Helpers */
    .password-toggle {
        color: #888;
        cursor: pointer;
        transition: color 0.2s;
    }

    .password-toggle:hover {
        color: var(--accent-color);
    }

    /* 5. Custom Checkbox */
    .custom-checkbox {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        user-select: none;
    }

    .custom-checkbox input {
        display: none;
    }

    .custom-checkbox span {
        width: 18px;
        height: 18px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        margin-right: 10px;
        position: relative;
    }

    .custom-checkbox input:checked + span {
        background-color: var(--focus-color);
        border-color: var(--focus-color);
    }

    .custom-checkbox span::after {
        content: "";
        position: absolute;
        display: none;
        left: 6px; top: 2px;
        width: 5px; height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .custom-checkbox input:checked + span::after {
        display: block;
    }

    .alert{
        background: none !important;
        border: none !important;
    }


    .auth-tabs {
        position: relative;
        width: 100%;
        display: flex;
        justify-content: center;
        height: 28px;
        overflow: hidden; /* বাইরের অংশ হাইড রাখবে */
        /* margin-bottom: 1rem !important */
    }

    .tab-wrapper {
        position: absolute;
        display: flex;
        align-items: center;
        gap: 20px;
        left: 0;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
    }

    .auth-tab {
        cursor: pointer;
        font-size: 11px;
        font-weight: 700;
        color: rgba(15, 45, 74, 0.4);
        white-space: nowrap;
        text-transform: uppercase;
        transition: all 0.4s ease;
    }

    .auth-tab.active {
        color: var(--accent-color);
        font-size: 13px;
    }

    .tab-indicator {
        position: absolute;
        bottom: 0;
        height: 2px;
        background: #0f9bd6;
        transition: all 0.5s ease;
        box-shadow: 0 0 10px rgba(15, 155, 214, 0.6);
    }

    /* 6. Responsive Adjustments */
    @media (max-width: 768px) {
        .login-card {
            /* flex-direction: column;
            margin: 20px auto;
            width: 92%;
            box-shadow: none; */
        }

        .login-image {
            display: none;
        }

        .login-form {
            padding: 30px 20px;
        }

        .login-title {
            font-size: 22px;
        }
    }
</style>
@endpush

