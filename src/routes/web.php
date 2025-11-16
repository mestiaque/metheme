<?php

use Illuminate\Support\Facades\Route;
use Encodex\Metheme\Http\Controllers\DataController;
use Encodex\Metheme\Http\Controllers\RoleController;
use Encodex\Metheme\Http\Controllers\UserController;
use Encodex\Metheme\Http\Middleware\LocaleMiddleware;
use Encodex\Metheme\Http\Controllers\ProfileController;
use Encodex\Metheme\Http\Controllers\SettingController;

Route::middleware([LocaleMiddleware::class])->group(function () {
    Route::get('/language/{locale?}', [DataController::class, 'changeLocale'])->name('language.change');
});

Route::group(['prefix' => 'encodex', 'as' => 'encodex.', 'middleware' => ['web', 'auth', LocaleMiddleware::class]], function () {
    Route::get('/', [DataController::class, 'index'])->name('dashboard');

    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/configurations', [SettingController::class, 'editConfigurations'])->name('configurations.edit');
    Route::put('/configurations', [SettingController::class, 'updateConfigurations'])->name('configurations.update');
    Route::get('/data/clear', [DataController::class, 'clearDataForm'])->name('data.clear.form');
    Route::post('/data/clear', [DataController::class, 'clearData'])->name('data.clear');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::resource('roles', RoleController::class);
});
include 'file.php';

require __DIR__.'/auth.php';
