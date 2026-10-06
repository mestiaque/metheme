<?php

use ME\Models\Setting;

if (!function_exists('get_setting')) {
    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function get_setting($key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('me_mail')) {
    /**
     * Send an e-mail with a Metheme template, using the SMTP settings saved on the
     * Mail Configuration page. Returns true when handed to the mailer.
     *
     *   me_mail('a@b.com', 'Hello', '<p>Your order is ready.</p>');  // common layout
     *   me_mail($emails, 'Code', '<p>Use this code</p>', ['otp' => 123456], 'auth');
     *   me_mail($email, 'Invoice', '', ['invoice' => $invoice], 'emails.invoice', queue: true);
     *
     * @param string|array $to       one address or a list
     * @param string       $content  HTML body (trusted; escape user input with e())
     * @param array        $data     extra variables for the template (title, otp, showGreeting, ...)
     * @param string       $template name from config('me_settings.mail_templates') or a Blade view
     * @param bool         $queue    queue the mail instead of sending now
     */
    function me_mail($to, string $subject, string $content = '', array $data = [], string $template = 'default', bool $queue = false): bool
    {
        $view = config('me_settings.mail_templates.' . $template, $template);

        if (!view()->exists($view)) {
            report(new \InvalidArgumentException("Mail template [{$template}] not found."));
            return false;
        }

        try {
            $mail = new \ME\Mail\TemplateMail($view, $subject, array_merge(['content' => $content], $data));
            $pending = \Illuminate\Support\Facades\Mail::to($to);
            $queue ? $pending->queue($mail) : $pending->send($mail);

            return true;
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }
}

if (!function_exists('me_sms')) {
    /**
     * Send an SMS through the gateway saved on the SMS Configuration page.
     * Returns true when the gateway accepted the message.
     *
     *   me_sms('01712345678', 'Your order is ready.');
     *   me_sms($phone, "Your code is {$otp}", hideMessage: true); // text not stored in the SMS log
     */
    function me_sms(string $to, string $message, bool $hideMessage = false): bool
    {
        try {
            return (bool) (\ME\Services\SmsService::send($to, $message, $hideMessage)['success'] ?? false);
        } catch (\Throwable $e) {
            report($e);
            return false;
        }
    }
}

if (!function_exists('me_change_log')) {
    /**
     * Log the before/after data of an action as one readable entry in the Activity Log.
     *
     *   $log = me_change_log('Order #12 updated', 'order.update')->watch($order, ['items']);
     *   ... update ...
     *   $log->save();
     *
     * See ME\Services\DataChangeLogger and doc.md for all options.
     */
    function me_change_log(?string $title = null, ?string $slug = null): \ME\Services\DataChangeLogger
    {
        return new \ME\Services\DataChangeLogger($title, $slug);
    }
}

if (!function_exists('me_prefix')) {
    /**
     * Admin URL prefix (.env METHEME_ROUTE_PREFIX, default "admin").
     */
    function me_prefix(): string
    {
        return (string) config('me_settings.route_prefix', 'admin');
    }
}

if (!function_exists('me_home_url')) {
    /**
     * Admin home page URL: route config('me_settings.home_route'), or the profile page when unset / missing.
     */
    function me_home_url(): string
    {
        $route = config('me_settings.home_route');

        return route($route && \Illuminate\Support\Facades\Route::has($route) ? $route : 'profile.edit');
    }
}

if (!function_exists('me_is_developer')) {
    /**
     * Developer mode: true when METHEME_DEVELOPER_MODE=true and the user's email is listed
     * in METHEME_DEVELOPER_EMAILS. Such users skip every permission check.
     */
    function me_is_developer($user = null): bool
    {
        $user = $user ?? auth()->user();

        if (!$user || !config('me_settings.developer_mode')) {
            return false;
        }

        $email = strtolower(trim((string) ($user->email ?? '')));

        return $email !== '' && in_array($email, (array) config('me_settings.developer_emails', []), true);
    }
}

if (!function_exists('me_developer_permission_keys')) {
    /**
     * Every "module.action" key in config('me_settings.developer_permissions').
     */
    function me_developer_permission_keys(): array
    {
        $keys = [];
        foreach ((array) config('me_settings.developer_permissions', []) as $module => $data) {
            foreach (explode(',', (string) ($data['actions'] ?? '')) as $action) {
                $action = trim($action);
                if ($action !== '') {
                    $keys[] = $module . '.' . $action;
                }
            }
        }

        return $keys;
    }
}

if (!function_exists('me_is_developer_only')) {
    /**
     * Whether a permission is reserved for developer mode (never granted through roles).
     */
    function me_is_developer_only($permission): bool
    {
        // Entries may be exact keys ("me_setting.mail") or wildcard patterns ("me.*", "me_*").
        foreach ((array) config('me_settings.developer_only_permissions', []) as $pattern) {
            if (\Illuminate\Support\Str::is((string) $pattern, (string) $permission)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('menu_trans')) {
    /**
     * Translate a sidebar/menu title. Menu titles are plain English ("Sales Report"),
     * but the translations live in the packages' own lang files, which follow the
     * "<namespace>::<namespace>.<text>" convention (me::me, kazitds::kazitds, ...).
     * The first package that translates the text for the current locale wins;
     * otherwise the app's own translation (or the text itself) is used.
     */
    function menu_trans(?string $text): string
    {
        if ($text === null || $text === '') {
            return '';
        }

        $translator = app('translator');
        foreach (array_keys($translator->getLoader()->namespaces()) as $namespace) {
            $key = "{$namespace}::{$namespace}.{$text}";
            if ($translator->hasForLocale($key)) {
                return $translator->get($key);
            }
        }

        return __($text);
    }
}

if (!function_exists('get_image')) {
    /**
     * URL of an image setting ('app_logo', 'app_ico', 'ecom_store_logo' …) from me_media.
     * Settings saved before media was added (a file name in storage/images/{key}/) still work.
     *
     * @param string $key
     * @param string|null $default   asset path used when the setting has no image
     * @param string|null $conversion  e.g. 'thumb'
     * @return string|null
     */
    function get_image($key, $default = null, $conversion = null)
    {
        try {
            if ($url = Setting::imageUrl($key, $conversion)) {
                return $url;
            }
            $filename = Setting::get($key);
        } catch (\Throwable $e) {
            $filename = null; // database not ready (fresh install, error pages)
        }

        if ($filename && !\Illuminate\Support\Str::isUuid($filename)) {
            return asset("storage/images/{$key}/{$filename}");
        }

        return $default ? asset($default) : null;
    }
}

if (!function_exists('me_media_url')) {
    /**
     * URL of a media file (Media model, id or uuid), optionally a conversion like 'thumb'.
     */
    function me_media_url($media, $conversion = null, $default = null)
    {
        if (!$media instanceof \ME\Models\Media) {
            $media = $media ? \ME\Models\Media::where(is_numeric($media) ? 'id' : 'uuid', $media)->first() : null;
        }

        return $media?->url($conversion) ?? ($default ? asset($default) : null);
    }
}


if (!function_exists('toBanglaNumber')) {
    function toBanglaNumber($number, $decimals = 0) {
        $number = (float) $number; // ensure numeric
        $formatted = number_format($number, $decimals);

        // Bangla conversion only if locale is 'bn'
        if (app()->getLocale() === 'bn') {
            $en = ['0','1','2','3','4','5','6','7','8','9'];
            $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            return str_replace($en, $bn, $formatted);
        }

        // English locale
        return $formatted;
    }
}

//banglaPhone
if (!function_exists('toBanglaPhone')) {
    function toBanglaPhone($phone) {
        // শুধুমাত্র বাংলা locale হলে কনভার্ট করবে
        if (app()->getLocale() === 'bn') {
            $en = ['0','1','2','3','4','5','6','7','8','9'];
            $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            return str_replace($en, $bn, $phone);
        }

        // অন্য যেকোনো locale হলে আসল ইংরেজি নম্বর ফেরত দেবে
        return $phone;
    }
}


if (!function_exists('formatDate')) {
    /**
     * Format date fully in Bangla (numbers + month)
     *
     * @param \DateTime|string|null $date
     * @param string $format
     * @return string
     */
    function formatDate($date, $format = 'd M, Y')
    {
        if (!$date) {
            return '';
        }

        // Convert string to Carbon instance if needed
        $carbonDate = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);

        $formatted = $carbonDate->format($format); // e.g. "12 Dec, 2025"

        if (app()->getLocale() === 'bn') {
            // Digits mapping
            $en = ['0','1','2','3','4','5','6','7','8','9'];
            $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            $formatted = str_replace($en, $bn, $formatted);

            // Month mapping
            $months = [
                'Jan' => 'জানুয়ারি',
                'Feb' => 'ফেব্রুয়ারি',
                'Mar' => 'মার্চ',
                'Apr' => 'এপ্রিল',
                'May' => 'মে',
                'Jun' => 'জুন',
                'Jul' => 'জুলাই',
                'Aug' => 'অগাস্ট',
                'Sep' => 'সেপ্টেম্বর',
                'Oct' => 'অক্টোবর',
                'Nov' => 'নভেম্বর',
                'Dec' => 'ডিসেম্বর',
            ];

            foreach ($months as $enMonth => $bnMonth) {
                $formatted = str_replace($enMonth, $bnMonth, $formatted);
            }
        }

        return $formatted;
    }
}

if (!function_exists('formatDateTime')) {
    /**
     * Format date with Bangla numbers, month, and time (hour:minute:second AM/PM)
     * Default format: 'd M, Y h:i:s A'
     *
     * @param \DateTime|string|null $date
     * @param string|null $format
     * @return string
     */
    function formatDateTime($date, $format = null)
    {
        if (!$date) {
            return '';
        }

        // Default format if not provided
        if (!$format) {
            $format = 'd M, Y h:i:s A';
        }

        // Convert string to Carbon instance if needed
        $carbonDate = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);

        $formatted = $carbonDate->format($format);

        if (app()->getLocale() === 'bn') {
            // Digits mapping
            $en = ['0','1','2','3','4','5','6','7','8','9'];
            $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            $formatted = str_replace($en, $bn, $formatted);

            // Month mapping
            $months = [
                'Jan' => 'জানুয়ারি',
                'Feb' => 'ফেব্রুয়ারি',
                'Mar' => 'মার্চ',
                'Apr' => 'এপ্রিল',
                'May' => 'মে',
                'Jun' => 'জুন',
                'Jul' => 'জুলাই',
                'Aug' => 'অগাস্ট',
                'Sep' => 'সেপ্টেম্বর',
                'Oct' => 'অক্টোবর',
                'Nov' => 'নভেম্বর',
                'Dec' => 'ডিসেম্বর',
            ];
            foreach ($months as $enMonth => $bnMonth) {
                $formatted = str_replace($enMonth, $bnMonth, $formatted);
            }

            // AM/PM mapping
            $ampm = [
                'AM' => 'পূর্বাহ্ণ',
                'PM' => 'অপরাহ্ণ',
            ];
            foreach ($ampm as $enAmpm => $bnAmpm) {
                $formatted = str_replace($enAmpm, $bnAmpm, $formatted);
            }
        }

        return $formatted;
    }

    if (!function_exists('banglaYear')) {
        /**
         * Format only year in Bangla (e.g. ২০২৫)
         *
         * @param \DateTime|string|null $date
         * @return string
         */
        function banglaYear($date = null)
        {
            if (!$date) {
                $date = now();
            }

            // Convert string to Carbon instance if needed
            $carbonDate = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);

            $year = $carbonDate->format('Y'); // e.g. "2025"

            if (app()->getLocale() === 'bn') {
                $en = ['0','1','2','3','4','5','6','7','8','9'];
                $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
                $year = str_replace($en, $bn, $year);
            }

            return $year;
        }
    }

}


if(!function_exists('can')){
    function can($permission)
    {
        return Auth::user()->hasPermission($permission);
    }
}