<?php

use Illuminate\Support\Facades\Route;
use ME\Http\Controllers\GeoLocationController;

Route::group(['prefix' => 'api/crm', 'middleware' => ['web', 'auth']], function () {
    
});

// Public (checkout / address forms): country -> division -> district -> upazila
Route::group(['prefix' => 'api/geo', 'middleware' => ['web', 'throttle:me-geo']], function () {
    Route::get('/countries', [GeoLocationController::class, 'countries'])->name('geo.countries');
    Route::get('/{geoLocation}/children', [GeoLocationController::class, 'children'])->whereNumber('geoLocation')->name('geo.children');
});
