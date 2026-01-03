@push('css')
<style>
    /* 1. Base Layout & Card */
    .login-card {
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
        width: 80px;
        height: 80px;
        background-color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        box-shadow: inset 6px 6px 8px rgba(0, 0, 0, 0.15),
                    inset -6px -6px 8px rgba(255, 255, 255, 0.9),
                    5px 5px 15px rgba(0, 0, 0, 0.2);
    }

    .login-avatar::before {
        content: "";
        position: absolute;
        top: 12%; left: 12%; width: 40%; height: 30%;
        background: rgba(255, 255, 255, 0.85);
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
        background: transparent !important;
        color: whitesmoke !important;
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

    /* 4. Password Toggle & Helpers */
    .password-toggle {
        color: #888;
        cursor: pointer;
        transition: color 0.2s;
    }

    .password-toggle:hover {
        color: var(--accent-color);
    }

    /* Autofill fix */
    input:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px transparent inset !important;
        -webkit-text-fill-color: white !important;
        transition: background-color 5000s ease-in-out 0s;
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

