<?php

use Illuminate\Support\Facades\Route;
use Isotope\Metronic\Http\Middlewares\LocaleMiddleware;

Route::group(['prefix' => 'crm', 'as' => 'crm.', 'middleware' => ['web', 'auth', 'authorization', LocaleMiddleware::class]], function () {
    Route::resource('projects', 'Isotope\CRM\Http\Controllers\ProjectController');
});