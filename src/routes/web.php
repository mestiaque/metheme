<?php

use Illuminate\Support\Facades\Route;
use ME\Http\Controllers\ActivityController;
use ME\Http\Controllers\DataController;
use ME\Http\Controllers\MenuController;
use ME\Http\Controllers\ProfileController;
use ME\Http\Controllers\RoleController;
use ME\Http\Controllers\SettingController;
use ME\Http\Controllers\UserController;
use ME\Http\Middleware\LocaleMiddleware;

Route::middleware(['web', LocaleMiddleware::class])->group(function () {
    Route::get('/language/{locale?}', [DataController::class, 'changeLocale'])->name('language.change');
    Route::get('/guest-demo', [DataController::class, 'guestDemo'])->name('guest.demo');
});

Route::group(['prefix' => 'me', 'as' => 'me.', 'middleware' => ['web', 'auth', LocaleMiddleware::class, 'activityLog']], function () {
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

    Route::get('/activities', [ActivityController::class, 'index'])->name('activity.index');
    Route::get('/activities/export', [ActivityController::class, 'export'])->name('activity.export');
    Route::get('/activities/statistics', [ActivityController::class, 'statistics'])->name('activity.statistics');
    Route::post('/activities/{activity}/logout-device', [ActivityController::class, 'logoutDevice'])->name('activity.logout-device');
    Route::get('/activities/{activity}', [ActivityController::class, 'show'])->name('activity.show');

    Route::get('/theme', [DataController::class, 'theme'])->name('theme');
    Route::get('/mail-layout-preview', [DataController::class, 'mailLayoutPreview'])->name('mail-layout-preview');

    Route::resource('menus', MenuController::class);
});

require __DIR__.'/file.php';
require __DIR__.'/auth.php';

Route::get('/favicon.svg', function () { return response(view('me::svg')) ->header('Content-Type', 'image/svg+xml'); })->name('favicon.svg');


