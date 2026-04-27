<?php

namespace ME\Http\Requests\Auth;

use ME\Models\User;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_name' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $userName = $this->input('user_name');
        $fieldType = filter_var($userName, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($fieldType, $userName)->first();

        // ইউজার না থাকলে বা ইনঅ্যাক্টিভ হলে
        if (!$user || (method_exists($user, 'isActive') && !$user->isActive())) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'user_name' => ($user && method_exists($user, 'isActive') && !$user->isActive())
                                ? 'Your account has been deactivated.'
                                : trans('me::me.auth_failed'),
            ]);
        }

        // লগইন চেষ্টা
        if (! Auth::attempt(
            [$fieldType => $userName, 'password' => $this->input('password')], // $this->password এর বদলে input() ব্যবহার করা ভালো
            $this->boolean('remember')
        )) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'user_name' => trans('me::me.auth_failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));
        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'user_name' => trans('me::me.auth_throttle', [
                'seconds' => toBanglaPhone($seconds),
                'minutes' => toBanglaPhone(ceil($seconds / 60)),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('user_name')).'|'.$this->ip());
    }
}
