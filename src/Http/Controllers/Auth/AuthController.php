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
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use ME\Providers\RouteServiceProvider;
use ME\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('me::auth.login');
    }

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
                ->withErrors(['user_name' => 'Your account has been deactivated. Please contact the administrator.']);
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function logOut(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }


    public function register(): View
    {
        return view('me::auth.registration');
    }

    public function registerStore(Request $request)
    {
        // ১. ভ্যালিডেশন লজিক ঠিক করা
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'min:8'],
            'type' => ['required', 'in:phone,email'],
        ];

        if ($request->type == 'phone') {
            $rules['identity'] = ['required', 'numeric', 'digits:11', 'unique:users,phone'];
        } else {
            $rules['identity'] = ['required', 'email', 'max:255', 'unique:users,email'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $otp = rand(100000, 999999);

        // ২. সেশনে ডেটা সেভ করা (Password hash করে রাখা ভালো)
        session([
            'reg_data' => [
                'name' => $request->name,
                'type' => $request->type,
                'identity' => $request->identity,
                'password' => Hash::make($request->password),
                'otp' => $otp,
            ]
        ]);

        // ৩. OTP পাঠানো
        $this->sendOtp($request->type, $request->identity, $otp);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully!',
            'otp_debug' => $otp // প্রোডাকশনে এটা বাদ দিবেন
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => ['required', 'digits:6']]);

        $sessionData = session('reg_data');

        if (!$sessionData || $sessionData['otp'] != $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP or session expired!'], 422);
        }

        // ৪. ইউজার তৈরি (ডাইনামিক কলাম সিলেকশন)
        $userData = [
            'name' => $sessionData['name'],
            'password' => $sessionData['password'],
            'is_active' => 1,
        ];

        if ($sessionData['type'] == 'email') {
            $userData['email'] = $sessionData['identity'];
        } else {
            $userData['phone'] = $sessionData['identity'];
        }

        $user = User::create($userData);

        event(new Registered($user));
        session()->forget('reg_data');
        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful!',
            'redirect' => url(RouteServiceProvider::HOME)
        ]);
    }

    private function sendOtp($type, $identity, $otp)
    {
        if ($type == 'phone') {
            Http::get("https://bulksmsbd.net/api/smsapi", [
                'api_key' => 'dBG4rYOLWW28f3ip15yW', // dBG4...
                'type' => 'text',
                'number' => $identity,
                'senderid' => '8809617624082',
                'message' => "Your registration OTP is: {$otp}"
            ]);
        } elseif ($type == 'email') {
            $companyName = "Dordam BD";
            // লোগোর পূর্ণ ইউআরএল দিন (যেমন: dordambd.com), শুধু ডোমেইন দিলে লোগো আসবে না
            $companyLogo = "dordambd.com";
            $currentYear = date('Y');

            $html = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Verify Your Email</title>
                </head>
                <body style='margin: 0; padding: 0; font-family: \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; background-color: #f0f2f5;'>
                    <table border='0' cellpadding='0' cellspacing='0' width='100%' style='padding: 40px 20px;'>
                        <tr>
                            <td align='center'>
                                <!-- Main Card -->
                                <table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 500px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08);'>
                                    <!-- Header with Logo -->
                                    <tr>
                                        <td align='center' style='padding: 40px 0 20px 0;'>
                                            <img src='{$companyLogo}' alt='{$companyName}' style='width: 120px; height: auto; display: block;'>
                                        </td>
                                    </tr>
                                    <!-- Content Section -->
                                    <tr>
                                        <td style='padding: 0 40px;'>
                                            <h2 style='color: #1a1a1a; font-size: 24px; font-weight: 700; margin: 0; text-align: center;'>Verify Your Account</h2>
                                            <p style='color: #4a4a4a; font-size: 16px; line-height: 1.6; margin-top: 20px; text-align: center;'>
                                                Hello, thank you for joining <strong>{$companyName}</strong>. Use the secure code below to complete your registration.
                                            </p>
                                        </td>
                                    </tr>
                                    <!-- OTP Section -->
                                    <tr>
                                        <td align='center' style='padding: 30px 40px;'>
                                            <div style='background-color: #f8faff; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 20px;'>
                                                <span style='font-size: 36px; font-weight: 800; letter-spacing: 10px; color: #0052cc; display: block; font-family: monospace;'>
                                                    {$otp}
                                                </span>
                                            </div>
                                            <p style='color: #64748b; font-size: 13px; margin-top: 15px;'>
                                                This code will expire in <span style='color: #ef4444; font-weight: 600;'>5 minutes</span>.
                                            </p>
                                        </td>
                                    </tr>
                                    <!-- Footer Info -->
                                    <tr>
                                        <td style='padding: 0 40px 40px 40px;'>
                                            <p style='color: #94a3b8; font-size: 13px; text-align: center; margin: 0; border-top: 1px solid #f1f5f9; padding-top: 25px;'>
                                                If you didn't request this, please ignore this email or contact support if you have questions.
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                                <!-- Copyright Section -->
                                <table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 500px;'>
                                    <tr>
                                        <td style='padding: 20px 0; text-align: center;'>
                                            <p style='color: #94a3b8; font-size: 12px; margin: 0;'>
                                                &copy; {$currentYear} <strong>{$companyName}</strong>. All rights reserved.
                                            </p>
                                            <p style='color: #94a3b8; font-size: 12px; margin: 5px 0 0 0;'>
                                                Dhaka, Bangladesh.
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </body>
                </html>
            ";

            // Mail::raw এর বদলে Mail::send ব্যবহার করুন HTML পাঠানোর জন্য
            Mail::send([], [], function ($message) use ($identity, $html) {
                $message->to($identity)
                        ->subject('Email Verification OTP')
                        ->html($html); // এখানে আপনার HTML ভেরিয়েবলটি পাস করুন
            });
        }
    }



    public function forgetPassword(): View
    {
        return view('me::auth.forget');
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
