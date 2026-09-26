<?php

namespace ME\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use ME\Models\Setting;

class MailConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('authorization:me_setting.mail');
    }

    public function edit()
    {
        $settings = [
            'mail_custom_enabled' => (bool) Setting::get('mail_custom_enabled', false),
            'mail_mailer'         => Setting::get('mail_mailer', config('mail.default')),
            'mail_host'           => Setting::get('mail_host', config('mail.mailers.smtp.host')),
            'mail_port'           => Setting::get('mail_port', config('mail.mailers.smtp.port')),
            'mail_encryption'     => Setting::get('mail_encryption', 'tls'),
            'mail_username'       => Setting::get('mail_username', config('mail.mailers.smtp.username')),
            'mail_from_address'   => Setting::get('mail_from_address', config('mail.from.address')),
            'mail_from_name'      => Setting::get('mail_from_name', config('mail.from.name')),
            'has_password'        => filled(Setting::get('mail_password')),
        ];

        return view('me::settings.mail-config', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'mail_mailer'       => 'required|in:smtp,sendmail,log',
            'mail_host'         => 'required_if:mail_mailer,smtp|nullable|string|max:255',
            'mail_port'         => 'required_if:mail_mailer,smtp|nullable|integer|min:1|max:65535',
            'mail_encryption'   => 'required|in:none,tls,ssl',
            'mail_username'     => 'nullable|string|max:255',
            'mail_password'     => 'nullable|string|max:255',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name'    => 'required|string|max:255',
        ]);

        Setting::set('mail_custom_enabled', $request->has('mail_custom_enabled'));
        foreach (['mail_mailer', 'mail_host', 'mail_port', 'mail_encryption', 'mail_username', 'mail_from_address', 'mail_from_name'] as $key) {
            Setting::set($key, $request->input($key));
        }

        // Blank password field keeps the saved one; it is stored encrypted
        if ($request->filled('mail_password')) {
            Setting::set('mail_password', Crypt::encryptString($request->mail_password));
        }

        return redirect()->route('me.mail-config.edit')
            ->with('success', __('me::me.mail_settings_saved'));
    }

    /**
     * Send a test email using the saved settings (they are applied at boot by MEServiceProvider).
     */
    public function test(Request $request)
    {
        $request->validate(['test_email' => 'required|email']);

        try {
            Mail::raw(__('me::me.test_mail_body', ['app' => config('app.name'), 'time' => now()->toDateTimeString()]), function ($message) use ($request) {
                $message->to($request->test_email)->subject(__('me::me.test_mail_subject', ['app' => config('app.name')]));
            });
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', __('me::me.test_mail_failed') . ' ' . Str::squish($e->getMessage()));
        }

        return back()->withInput()->with('success', __('me::me.test_mail_sent', ['email' => $request->test_email]));
    }
}
