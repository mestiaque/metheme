<?php

namespace Encodex\Metheme;

use Illuminate\Routing\Router;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Encodex\Metheme\Http\Middleware\AuthorizationMiddleware;

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
        $this->publishes([
            __DIR__ . '/public' => public_path('/'),
        ], 'metheme-assets');

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
    }

    private function registerAuthProvider()
    {
        $this->app->register(\Encodex\Metheme\Providers\AuthServiceProvider::class);
    }
}
