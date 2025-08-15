<?php

use Illuminate\Support\Facades\Route;
use Isotope\Metronic\Http\Middlewares\LocaleMiddleware;

Route::group(['prefix' => 'crm', 'middleware' => ['web', 'auth', 'authorization', LocaleMiddleware::class]], function () {

});