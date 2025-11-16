<?php

namespace Encodex\Metheme\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->extend('translation.loader', function ($loader, $app) {
            return new class($app['files'], $app['path.lang']) extends FileLoader {
                public function load($locale, $group, $namespace = null)
                {
                    // যখন লোকেল 'bn' বা 'en' এবং গ্রুপ 'messages' বা '*', তখন locale নামের ফাইল থেকে লোড করো
                    if (($group === 'messages' || $group === '*') && in_array($locale, ['bn', 'en'])) {
                        return parent::load($locale, $locale, $namespace);  // অর্থাৎ 'bn.php' বা 'en.php'
                    }
                    // অন্যথায় ডিফল্ট ফাইল থেকে লোড
                    return parent::load($locale, $group, $namespace);
                }
            };
        });

        $this->app->extend('translator', function ($translator, $app) {
            return new Translator($app['translation.loader'], $app['config']['app.locale']);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(base_path('routes/api.php'));
    }
}
