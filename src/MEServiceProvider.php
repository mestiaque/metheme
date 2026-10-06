<?php

namespace ME;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ME\Console\ImportMailSmsFromEnv;
use ME\Console\MediaCleanup;
use ME\Console\MediaConversions;
use ME\Console\MediaImport;
use ME\Console\SyncGeoLocations;
use ME\Http\Controllers\DataController;
use ME\Http\Middleware\ActivityLogger;
use ME\Http\Middleware\AuthorizationMiddleware;
use ME\Http\Middleware\ExposeDeveloperPermissions;
use ME\Http\Middleware\LocaleMiddleware;
use ME\Models\Setting;
use ME\Providers\AuthServiceProvider;
use ME\Services\MailLogger;
use ME\Services\RoleHierarchy;

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
        // Helpers first: route files use them (e.g. me_prefix()).
        if (file_exists(__DIR__.'/Http/Helpers/helpers.php')) {
            require_once __DIR__.'/Http/Helpers/helpers.php';
        }
        if (file_exists(__DIR__.'/Http/Helpers/PermissionHelper.php')) {
            require_once __DIR__.'/Http/Helpers/PermissionHelper.php';
        }

        // Own counter for the geo API (address dropdowns); a plain "throttle:120,1" shares one counter
        // per visitor with every other throttled route of the app
        RateLimiter::for('me-geo', fn (Request $request) => Limit::perMinute(120)->by($request->ip()));

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/routes/auth.php');
        $this->app->booted(fn () => $this->registerHomeRoute());

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'ME');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'me');
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'ME');
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'me');

        /*
        |--------------------------------------------------------------------------
        | Publish Public Assets
        |--------------------------------------------------------------------------
        */
        $this->publishes([__DIR__.'/public' => public_path('/')], 'metheme-assets');

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

        if ($filesystem->exists(__DIR__.'/Config/auth.php')) {
            $this->publishes([
                __DIR__.'/Config/auth.php' => config_path('auth.php'),
            ], 'metheme-auth-config');
        }

        /*
        |--------------------------------------------------------------------------
        | Publish Error Pages
        |--------------------------------------------------------------------------
        */
        if ($filesystem->exists(__DIR__.'/resources/views/errors')) {
            $this->publishes([
                __DIR__.'/resources/views/errors' => resource_path('views/errors'),
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

        // After every package has merged its permissions, hide developer-only ones
        // so no role form (in any package) can offer them.
        $this->app->booted(fn () => $this->hideDeveloperOnlyPermissions());
    }

    /**
     * Remove config('me_settings.developer_only_permissions') from config('permissions').
     * hasPermission() grants them only in developer mode.
     */
    private function hideDeveloperOnlyPermissions()
    {
        $hidden = (array) config('me_settings.developer_only_permissions', []);
        $permissions = config('permissions');

        if (! $hidden || ! is_array($permissions)) {
            return;
        }

        foreach ($permissions as $module => $data) {
            if (! is_array($data) || ! isset($data['actions'])) {
                continue;
            }

            $actions = array_filter(
                array_map('trim', explode(',', (string) $data['actions'])),
                fn ($action) => $action !== '' && ! me_is_developer_only($module.'.'.$action)
            );

            if ($actions) {
                $permissions[$module]['actions'] = implode(',', $actions);
            } else {
                unset($permissions[$module]);
            }
        }

        config(['permissions' => $permissions]);
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(RoleHierarchy::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ImportMailSmsFromEnv::class,
                SyncGeoLocations::class,
                MediaImport::class,
                MediaConversions::class,
                MediaCleanup::class,
            ]);
        }
        /*
        |--------------------------------------------------------------------------
        | Merge Config Files (no vendor:publish needed)
        |--------------------------------------------------------------------------
        | This ensures that even if config files are not published,
        | package defaults will still be merged and available via config().
        |--------------------------------------------------------------------------
        */
        if (file_exists(__DIR__.'/Config/sidebar.php')) {
            $this->mergeConfigFrom(__DIR__.'/Config/sidebar.php', 'sidebar');
        }

        if (file_exists(__DIR__.'/Config/permissions.php')) {
            $this->mergeConfigFrom(__DIR__.'/Config/permissions.php', 'permissions');
        }

        // If you want to extend Laravel auth config (optional)
        if (file_exists(__DIR__.'/Config/auth.php')) {
            $this->mergeConfigFrom(__DIR__.'/Config/auth.php', 'auth');
        }

        if (file_exists(__DIR__.'/Config/me_settings.php')) {
            $this->mergeConfigFrom(__DIR__.'/Config/me_settings.php', 'me_settings');
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

        // Runs after the session starts, so the logged-in developer can be detected.
        $router->pushMiddlewareToGroup('web', ExposeDeveloperPermissions::class);
    }

    private function registerAuthProvider()
    {
        $this->app->register(AuthServiceProvider::class);
    }

    /**
     * Override mail and SMS config with the values saved on the
     * Mail Configuration / SMS Configuration pages (settings table).
     */
    private function applyStoredMailAndSmsConfig()
    {
        try {
            $settings = Setting::whereIn('key', [
                'mail_mailer', 'mail_host', 'mail_port', 'mail_encryption',
                'mail_username', 'mail_password', 'mail_from_address', 'mail_from_name',
                'enable_sms', 'sms_api_url', 'sms_api_key', 'sms_sender_id', 'sms_balance_url',
                'app_name', 'app_email',
            ])->pluck('value', 'key');
        } catch (\Throwable $e) {
            return; // database or settings table not ready yet (fresh install, migrations)
        }

        $decrypt = function ($value) {
            try {
                return filled($value) ? Crypt::decryptString($value) : null;
            } catch (\Throwable $e) {
                return null; // saved with a different APP_KEY
            }
        };
        $get = fn ($key) => filled($settings[$key] ?? null) ? $settings[$key] : null;

        /*
        | Mail and SMS always come from the Mail / SMS Configuration pages (settings table).
        | .env MAIL_* / SMS_* values are never used. Until SMTP is configured, mail goes to the
        | log instead of being sent. Run "php artisan metheme:import-mail-sms-env" once to copy
        | existing .env values into the database.
        */
        $mailer = $get('mail_mailer') ?: 'smtp';
        $mailReady = $mailer !== 'smtp' || $get('mail_host');
        $host = parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'localhost';

        config([
            'mail.default' => $mailReady ? $mailer : 'log',
            'mail.mailers.smtp.url' => null,
            'mail.mailers.smtp.host' => $get('mail_host'),
            'mail.mailers.smtp.port' => (int) ($get('mail_port') ?? 587),
            // Laravel's smtp scheme: "smtps" = implicit SSL (port 465), "smtp" = STARTTLS when offered
            'mail.mailers.smtp.scheme' => $get('mail_encryption') === 'ssl' ? 'smtps' : 'smtp',
            'mail.mailers.smtp.username' => $get('mail_username'),
            'mail.mailers.smtp.password' => $decrypt($get('mail_password')),
            'mail.from.address' => $get('mail_from_address') ?: ($get('app_email') ?: 'noreply@'.$host),
            'mail.from.name' => $get('mail_from_name') ?: ($get('app_name') ?: config('app.name')),
        ]);

        config([
            'services.sms_enabled' => (bool) $get('enable_sms'),
            'services.sms_api_url' => $get('sms_api_url'),
            'services.sms_api_key' => $decrypt($get('sms_api_key')),
            'services.sms_sender_id' => $get('sms_sender_id'),
            'services.sms_balance_url' => $get('sms_balance_url'),
        ]);
    }

    /**
     * /{prefix} redirects to the admin home (me_home_url()) — only when no package put its own page there
     * (e.g. ecom's dashboard). Runs after every provider booted, so their routes are already registered.
     */
    private function registerHomeRoute(): void
    {
        $uri = me_prefix() ?: '/';

        if ($this->app->routesAreCached() || isset(Route::getRoutes()->get('GET')[$uri])) {
            return;
        }

        Route::middleware(['web', 'auth', LocaleMiddleware::class, 'activityLog'])
            ->get($uri, [DataController::class, 'home'])
            ->name('me.home');
        Route::getRoutes()->refreshNameLookups();
    }
}
