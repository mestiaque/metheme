<?php

namespace ME\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ME\Http\Controllers\Controller;

class DataController extends Controller
{
    public function __construct()
    {
        $this->middleware('authorization:me.clearData')->only(['clearData', 'clearDataForm']);
        $this->middleware('authorization:me.dashboard')->only(['index']);
        $this->middleware('authorization:me.theme')->only(['theme']);
        $this->middleware('authorization:me.mailLayoutPreview')->only(['mailLayoutPreview']);
    }

    public function index()
    {
        return view('me::dashboard');
    }

    public function clearDataForm()
    {
        return view('me::settings.clear');
    }

    public function clearData(Request $request)
    {
        $request->validate([
            'confirm_text' => 'required|in:CLEAR ALL DATA',
        ]);

        try {
            // Preserve these tables (won’t be deleted)
            $preserveTables = [
                'users',
                'roles',
                'role_user',
                'migrations', // optional, can be removed if needed
            ];

            // Get all table names dynamically
            $allTables = collect(DB::select('SHOW TABLES'))
                ->map(function ($table) {
                    $property = 'Tables_in_' . DB::getDatabaseName();
                    return $table->$property;
                })
                ->filter(function ($table) use ($preserveTables) {
                    return !in_array($table, $preserveTables);
                })
                ->values();

            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            foreach ($allTables as $table) {
                try {
                    DB::table($table)->truncate();
                } catch (\Exception $e) {
                    // কিছু টেবিল truncate করা না গেলে delete fallback
                    try {
                        DB::table($table)->delete();
                    } catch (\Exception $e2) {
                        // still skip silently
                    }
                }
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return redirect()->back()->with('success', 'All data cleared successfully. Users and roles are preserved.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error clearing data: ' . $e->getMessage());
        }
    }

    public function changeLocale($locale = 'en')
    {
        session(['locale' => $locale]);
        app()->setLocale($locale);
        return redirect()->back();
    }

    public function theme()
    {
        return view('me::theme');
    }

    public function guestDemo()
    {
        session()->flash('success', 'Welcome to the guest demo page!');
        return view('me::guest-demo');
    }

    public function mailLayoutPreview()
    {
        $companyName = get_setting('app_name', config('app.name', 'M.ESTIAQUE'));
        $companyLogo = route('app_logo.show');
        $currentYear = date('Y');

        $authWithOtp = view('me::mail.auth-layout', [
            'companyName' => $companyName,
            'companyLogo' => $companyLogo,
            'currentYear' => $currentYear,
            'otp' => '847291',
            'content' => '<h3 style="font-size:20px;font-weight:700;color:#1e293b;margin:0 0 12px 0;">Verify Your Email</h3><p style="color:#475569;font-size:14px;margin:0 0 10px 0;">Hello <strong>John Doe</strong>,</p><p style="color:#475569;font-size:14px;margin:0;">Use the OTP code below to verify your email address. The code is valid for 5 minutes.</p>',
        ])->render();

        $authNoOtp = view('me::mail.auth-layout', [
            'companyName' => $companyName,
            'companyLogo' => $companyLogo,
            'currentYear' => $currentYear,
            'otp' => null,
            'content' => '<h3 style="font-size:20px;font-weight:700;color:#1e293b;margin:0 0 12px 0;">Password Reset</h3><p style="color:#475569;font-size:14px;margin:0 0 10px 0;">Hello <strong>John Doe</strong>,</p><p style="color:#475569;font-size:14px;margin:0 0 16px 0;">We received a request to reset your account password. Click the button below to proceed.</p><div style="text-align:center;margin:24px 0;"><a href="#" style="background:#0052cc;color:#fff;padding:12px 32px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;display:inline-block;">Reset Password</a></div>',
        ])->render();

        $noticeHtml = view('me::mail.notice-layout', [
            'companyName' => $companyName,
            'companyLogo' => $companyLogo,
            'currentYear' => $currentYear,
            'title' => 'Important Platform Update',
            'content' => '<p>Hello <strong>John Doe</strong>,</p><p>We are writing to inform you about an important update to our platform that will affect all users.</p><div class="highlight-box"><strong>What\'s New in This Update:</strong><ul><li>Enhanced security features and 2FA support</li><li>Improved dashboard with real-time analytics</li><li>New role-based permission controls</li></ul></div><p>These changes will take effect starting <strong>May 1, 2026</strong>. No action is required on your end.</p>',
            'showGreeting' => true,
            'greetings' => [
                'Best wishes from the team!',
                'Take care and stay healthy!',
                'Warm regards!',
                'Stay blessed!',
            ],
        ])->render();

        return view('me::mail.layout-preview', compact('authWithOtp', 'authNoOtp', 'noticeHtml'));
    }
}
