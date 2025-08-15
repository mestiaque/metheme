<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/crm', 'middleware' => ['web', 'auth']], function () {
    
});