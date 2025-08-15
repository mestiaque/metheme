<?php

namespace Isotope\CRM;

use Illuminate\Support\ServiceProvider;

class CRMServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'crm');
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'crm');
    }
    public function register() {
        $this->mergeConfigFrom(__DIR__ . '/Config/sidebar.php', 'sidebar.crm');

        $ipermission = include __DIR__.'/Config/ipermissions.php';
        $config = $this->app->make('config');
        $config->set('ipermissions', array_merge($config->get('ipermissions', []), $ipermission));
    }

}
