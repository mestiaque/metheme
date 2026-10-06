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
            // Database only (.env MAIL_* values are not used)
            'mail_mailer'         => Setting::get('mail_mailer', 'smtp'),
            'mail_host'           => Setting::get('mail_host'),
            'mail_port'           => Setting::get('mail_port', 587),
            'mail_encryption'     => Setting::get('mail_encryption', 'tls'),
            'mail_username'       => Setting::get('mail_username'),
            'mail_from_address'   => Setting::get('mail_from_address'),
            'mail_from_name'      => Setting::get('mail_from_name', get_setting('app_name', config('app.name'))),
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

        $logKeys = ['mail_mailer', 'mail_host', 'mail_port', 'mail_encryption', 'mail_username', 'mail_password', 'mail_from_address', 'mail_from_name'];
        $before = Setting::snapshot($logKeys);

        foreach (['mail_mailer', 'mail_host', 'mail_port', 'mail_encryption', 'mail_username', 'mail_from_address', 'mail_from_name'] as $key) {
            Setting::set($key, $request->input($key));
        }

        // Blank password field keeps the saved one; it is stored encrypted
        if ($request->filled('mail_password')) {
            Setting::set('mail_password', Crypt::encryptString($request->mail_password));
        }

        // mail_password is a hidden field: the log only says it changed, never the value
        me_change_log('Mail settings updated', 'settings.mail')->record($before, Setting::snapshot($logKeys));

        return redirect()->route('mail-config.edit')
            ->with('success', __('me::me.mail_settings_saved'));
    }

    /**
     * Send a test email using the saved settings (they are applied at boot by MEServiceProvider).
     */
    public function test(Request $request)
    {
        $templates = (array) config('me_settings.mail_templates', []);

        $request->validate([
            'test_email'    => 'required|email',
            'test_template' => ['nullable', 'in:' . implode(',', array_keys($templates))],
            'test_subject'  => 'nullable|string|max:200',
            'test_message'  => 'nullable|string|max:20000',
        ]);

        // Sent through the same mail template system as me_mail(), so the test shows the real design
        $template = $request->input('test_template') ?: 'default';
        $app = get_setting('app_name', config('app.name'));

        // Own subject / message when given, otherwise the default test text
        $subject = trim((string) $request->input('test_subject')) ?: __('me::me.test_mail_subject', ['app' => $app]);
        $message = $this->cleanHtml((string) $request->input('test_message'));
        $content = $message !== ''
            ? $message
            : '<p>' . e(__('me::me.test_mail_body', ['app' => $app, 'time' => now()->toDateTimeString()])) . '</p>';

        try {
            Mail::to($request->test_email)->send(new \ME\Mail\TemplateMail($templates[$template], $subject, [
                'content'      => $content,
                'otp'          => $template === 'auth' ? '123456' : null,
                'showGreeting' => true,
            ]));
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', __('me::me.test_mail_failed') . ' ' . Str::squish($e->getMessage()));
        }

        return back()->withInput()->with('success', __('me::me.test_mail_sent', ['email' => $request->test_email]));
    }

    /**
     * Keep only what the editor produces: bold, italic, underline, text color, lists, links,
     * images and paragraphs. Scripts, event attributes and javascript: links are removed;
     * style attributes keep only color / background-color. Returns '' for an empty editor.
     */
    private function cleanHtml(string $html): string
    {
        $html = strip_tags($html, '<p><br><b><strong><i><em><u><ul><ol><li><a><span><div><font><img>');
        $html = preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);      // onclick=... etc.

        // style="...": keep only color / background-color declarations
        $html = preg_replace_callback('/\s+style\s*=\s*("([^"]*)"|\'([^\']*)\')/i', function ($m) {
            $css = $m[2] !== '' ? $m[2] : ($m[3] ?? '');
            $kept = [];
            foreach (explode(';', $css) as $rule) {
                if (preg_match('/^\s*(color|background-color)\s*:\s*([#a-z0-9(),.\s%]+)$/i', $rule, $r)) {
                    $kept[] = strtolower($r[1]) . ': ' . trim($r[2]);
                }
            }
            return $kept ? ' style="' . implode('; ', $kept) . '"' : '';
        }, $html);

        // links: no javascript:/data:/vbscript:
        $html = preg_replace('/href\s*=\s*("|\')\s*(javascript|data|vbscript):[^"\']*\1/i', 'href="#"', $html);
        // images: only http(s) or embedded data:image/...
        $html = preg_replace('/<img\b(?![^>]*\bsrc\s*=\s*("|\')\s*(https?:|data:image\/(png|jpe?g|gif|webp);base64,))[^>]*>/i', '', $html);
        // images: drop any style, never wider than the mail card
        $html = preg_replace_callback('/<img\b[^>]*>/i', function ($m) {
            $tag = preg_replace('/\s+style\s*=\s*("[^"]*"|\'[^\']*\')/i', '', $m[0]);
            return preg_replace('/^<img\b/i', '<img style="max-width: 100%; height: auto;"', $tag);
        }, $html);

        return trim(strip_tags($html, '<img>')) === '' ? '' : trim($html);
    }
}
