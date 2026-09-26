<?php

namespace ME;

use Illuminate\Routing\Router;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use ME\Http\Middleware\ActivityLogger;
use ME\Http\Middleware\AuthorizationMiddleware;
use ME\Models\Setting;
use ME\Services\MailLogger;

class MEServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(Filesystem $filesystem)
    {
        /*
        |--------------------------------------------------------------------------
        | Load Package Resources
        |--------------------------------------------------------------------------
        */
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/auth.php');

        if (file_exists(__DIR__ . '/Http/Helpers/helpers.php')) {
            require_once __DIR__ . '/Http/Helpers/helpers.php';
        }
        if (file_exists(__DIR__ . '/Http/Helpers/PermissionHelper.php')) {
            require_once __DIR__ . '/Http/Helpers/PermissionHelper.php';
        }

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'ME');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'me');
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'ME');
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'me');

        /*
        |--------------------------------------------------------------------------
        | Publish Public Assets
        |--------------------------------------------------------------------------
        */
        $this->publishes([ __DIR__ . '/public' => public_path('/'), ], 'metheme-assets');

        /*
        |--------------------------------------------------------------------------
        | Publish Config Files
        |--------------------------------------------------------------------------
        */
        // if ($filesystem->exists(__DIR__ . '/Config/sidebar.php')) {
        //     $this->publishes([
        //         __DIR__ . '/Config/sidebar.php' => config_path('sidebar.php'),
        //     ], 'metheme-config');
        // }

        // if ($filesystem->exists(__DIR__ . '/Config/permissions.php')) {
        //     $this->publishes([
        //         __DIR__ . '/Config/permissions.php' => config_path('permissions.php'),
        //     ], 'metheme-config');
        // }

        if ($filesystem->exists(__DIR__ . '/Config/auth.php')) {
            $this->publishes([
                __DIR__ . '/Config/auth.php' => config_path('auth.php'),
            ], 'metheme-auth-config');
        }

        /*
        |--------------------------------------------------------------------------
        | Publish Error Pages
        |--------------------------------------------------------------------------
        */
        if ($filesystem->exists(__DIR__ . '/resources/views/errors')) {
            $this->publishes([
                __DIR__ . '/resources/views/errors' => resource_path('views/errors'),
            ], 'metheme-errors');
        }
        // php artisan vendor:publish --tag=metheme-errors --force

        /*
        |--------------------------------------------------------------------------
        | Register Middleware
        |--------------------------------------------------------------------------
        */
        $this->registerMiddleware();
        $this->registerAuthProvider();
        $this->applyStoredMailAndSmsConfig();

        Event::listen(MessageSending::class, [MailLogger::class, 'sending']);
        Event::listen(MessageSent::class, [MailLogger::class, 'sent']);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        /*
        |--------------------------------------------------------------------------
        | Merge Config Files (no vendor:publish needed)
        |--------------------------------------------------------------------------
        | This ensures that even if config files are not published,
        | package defaults will still be merged and available via config().
        |--------------------------------------------------------------------------
        */
        if (file_exists(__DIR__ . '/Config/sidebar.php')) {
            $this->mergeConfigFrom(__DIR__ . '/Config/sidebar.php', 'sidebar');
        }

        if (file_exists(__DIR__ . '/Config/permissions.php')) {
            $this->mergeConfigFrom(__DIR__ . '/Config/permissions.php', 'permissions');
        }

        // If you want to extend Laravel auth config (optional)
        if (file_exists(__DIR__ . '/Config/auth.php')) {
            $this->mergeConfigFrom(__DIR__ . '/Config/auth.php', 'auth');
        }

        if (file_exists(__DIR__ . '/Config/me_settings.php')) {
            $this->mergeConfigFrom(__DIR__ . '/Config/me_settings.php', 'me_settings');
        }
    }

    /**
     * Register custom middleware alias.
     */
    private function registerMiddleware()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('authorization', AuthorizationMiddleware::class);
        $router->aliasMiddleware('activityLog', ActivityLogger::class);
        $router->aliasMiddleware('activity.logger', ActivityLogger::class);
    }

    private function registerAuthProvider()
    {
        $this->app->register(\ME\Providers\AuthServiceProvider::class);
    }

    /**
     * Override mail and SMS config with the values saved on the
     * Mail Configuration / SMS Configuration pages (settings table).
     */
    private function applyStoredMailAndSmsConfig()
    {
        try {
            $settings = Setting::whereIn('key', [
                'mail_custom_enabled', 'mail_mailer', 'mail_host', 'mail_port', 'mail_encryption',
                'mail_username', 'mail_password', 'mail_from_address', 'mail_from_name',
                'sms_api_url', 'sms_api_key', 'sms_sender_id', 'sms_balance_url',
            ])->pluck('value', 'key');
        } catch (\Throwable $e) {
            return; // database or settings table not ready yet (fresh install, migrations) - keep .env values
        }

        $decrypt = function ($value) {
            try {
                return filled($value) ? Crypt::decryptString($value) : null;
            } catch (\Throwable $e) {
                return null; // saved with a different APP_KEY
            }
        };

        if (!empty($settings['mail_custom_enabled'])) {
            config([
                'mail.default'               => $settings['mail_mailer'] ?? 'smtp',
                'mail.mailers.smtp.host'     => $settings['mail_host'] ?? null,
                'mail.mailers.smtp.port'     => (int) ($settings['mail_port'] ?? 587),
                // Laravel's smtp scheme: "smtps" = implicit SSL (port 465), "smtp" = STARTTLS when offered
                'mail.mailers.smtp.scheme'   => ($settings['mail_encryption'] ?? null) === 'ssl' ? 'smtps' : 'smtp',
                'mail.mailers.smtp.username' => $settings['mail_username'] ?? null,
                'mail.mailers.smtp.password' => $decrypt($settings['mail_password'] ?? null),
                'mail.from.address'          => $settings['mail_from_address'] ?? config('mail.from.address'),
                'mail.from.name'             => $settings['mail_from_name'] ?? config('mail.from.name'),
            ]);
        }

        if (filled($settings['sms_api_url'] ?? null)) {
            config([
                'services.sms_api_url'   => $settings['sms_api_url'],
                'services.sms_api_key'   => $decrypt($settings['sms_api_key'] ?? null),
                'services.sms_sender_id' => $settings['sms_sender_id'] ?? null,
                'services.sms_balance_url' => $settings['sms_balance_url'] ?? null,
            ]);
        }
    }
}
