<?php

namespace ME\Http\Controllers\Auth;

use ME\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use ME\Providers\RouteServiceProvider;
use ME\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function login(): View
    {
        return view('me::auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function loginStore(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Check if user is active (User মডেলে isActive() মেথড থাকতে হবে)
        if (method_exists(Auth::user(), 'isActive') && !Auth::user()->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been deactivated. Please contact the administrator.']);
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function logOut(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Display the registration view.
     */
    public function register(): View
    {
        return view('me::auth.registration');
    }

    /**
     * ধাপ ১: তথ্য যাচাই এবং সেশনে রাখা + OTP পাঠানো
     */
    public function registerStoreX(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $otp = rand(100000, 999999);

        // তথ্যগুলো সেশনে সেভ করে রাখা
        session([
            'reg_data' => [
                'name' => $request->name,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'otp' => $otp,
            ]
        ]);

        // SMS পাঠানো
        $this->sendOtpSms($request->phone, $otp);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent to your phone. Please verify.',
            'step' => 'otp_verify'
        ]);
    }

    public function registerStore(Request $request)
    {
        // Ekhane 'password' confirm check hobe.
        // Jodi confirmed field na thake, tobe request theke 'confirmed' rule shoriye nin.
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8'], // Confirmation chara simple rakhlam
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $otp = rand(100000, 999999);

        session([
            'reg_data' => [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'otp' => $otp,
            ]
        ]);

        // Send OTP (Mail ba SMS)
        // $this->sendOtpEmail($request->email, $otp);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully!'
        ]);
    }

    /**
     * ধাপ ২: OTP চেক করা এবং সেশনের তথ্য দিয়ে ইউজার তৈরি করা
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $sessionData = session('reg_data');

        if (!$sessionData || $sessionData['otp'] != $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP or session expired!'], 422);
        }

        // এবার ডাটাবেসে ইউজার তৈরি করুন
        $user = User::create([
            'name' => $sessionData['name'],
            'phone' => $sessionData['phone'],
            'password' => $sessionData['password'],
            'status' => 1,
            'phone_verified_at' => now(),
        ]);

        // ইভেন্ট ফায়ার করা (যদি প্রয়োজন হয়)
        event(new Registered($user));

        // সেশন ক্লিয়ার করা
        session()->forget('reg_data');

        // লগইন করানো
        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful!',
            'redirect' => url(RouteServiceProvider::HOME)
        ]);
    }

    /**
     * BulkSMSBD API Integration
     */
    private function sendOtpSms($phone, $otp)
    {
        // পূর্ণাঙ্গ API URL ব্যবহার করুন
        Http::get("https://bulksmsbd.net/api/smsapi", [
            'api_key' => 'dBG4rYOLWW28f3ip15yW',
            'type' => 'text',
            'number' => $phone,
            'senderid' => '8809617624082',
            'message' => "Your registration OTP is: {$otp}"
        ]);
    }



    /**
     * ধাপ ১: ফোন নম্বর যাচাই এবং OTP পাঠানো (Forget Password)
     */
    public function forgetPasswordStore(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'exists:users,phone'], // নম্বরটি ডাটাবেসে থাকতে হবে
        ]);

        $otp = rand(100000, 999999);

        // সেশনে ফোন এবং OTP রাখা
        session([
            'reset_phone' => $request->phone,
            'reset_otp' => $otp
        ]);

        // SMS পাঠানো
        $this->sendOtpSms($request->phone, $otp);

        return response()->json([
            'success' => true,
            'message' => 'Reset OTP sent to your phone.',
            'step' => 'reset_otp_verify'
        ]);
    }

    /**
     * ধাপ ২: OTP ভেরিফাই এবং নতুন পাসওয়ার্ড সেট করা
     */
    public function resetPasswordStore(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $sessionPhone = session('reset_phone');
        $sessionOtp = session('reset_otp');

        // চেক করা সেশনে ডাটা আছে কি না এবং OTP মিলছে কি না
        if (!$sessionPhone || $sessionOtp != $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP or session expired!'], 422);
        }

        // পাসওয়ার্ড আপডেট করা
        $user = User::where('phone', $sessionPhone)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // সেশন ক্লিয়ার করা
            session()->forget(['reset_phone', 'reset_otp']);

            return response()->json([
                'success' => true,
                'message' => 'Password reset successful! Please login.',
                'redirect' => route('login')
            ]);
        }

        return response()->json(['success' => false, 'message' => 'User not found!'], 404);
    }

}
