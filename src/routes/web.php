<?php

use Illuminate\Support\Facades\Route;
use ME\Http\Controllers\ActivityController;
use ME\Http\Controllers\DataController;
use ME\Http\Controllers\MailConfigController;
use ME\Http\Controllers\MailLogController;
use ME\Http\Controllers\MediaController;
use ME\Http\Controllers\MenuController;
use ME\Http\Controllers\MenuSearchController;
use ME\Http\Controllers\ProfileController;
use ME\Http\Controllers\RoleController;
use ME\Http\Controllers\SettingController;
use ME\Http\Controllers\SmsConfigController;
use ME\Http\Controllers\SmsLogController;
use ME\Http\Controllers\UserController;
use ME\Http\Middleware\LocaleMiddleware;

Route::middleware(['web', LocaleMiddleware::class])->group(function () {
    Route::get('/language/{locale?}', [DataController::class, 'changeLocale'])->name('language.change');
    Route::get('/guest-demo', [DataController::class, 'guestDemo'])->name('guest.demo');
    Route::get('/menu-search', [MenuSearchController::class, 'search'])->name('nav.menuSearch');

});

// URL prefix comes from config('me_settings.route_prefix') (.env METHEME_ROUTE_PREFIX). Route names have no prefix.
Route::group(['prefix' => me_prefix(), 'middleware' => ['web', 'auth', LocaleMiddleware::class, 'activityLog']], function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/configurations', [SettingController::class, 'editConfigurations'])->name('configurations.edit');
    Route::put('/configurations', [SettingController::class, 'updateConfigurations'])->name('configurations.update');

    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('/mail-config', [MailConfigController::class, 'edit'])->name('mail-config.edit');
    Route::put('/mail-config', [MailConfigController::class, 'update'])->name('mail-config.update');
    Route::post('/mail-config/test', [MailConfigController::class, 'test'])->name('mail-config.test');

    Route::get('/sms-config', [SmsConfigController::class, 'edit'])->name('sms-config.edit');
    Route::put('/sms-config', [SmsConfigController::class, 'update'])->name('sms-config.update');
    Route::post('/sms-config/test', [SmsConfigController::class, 'test'])->name('sms-config.test');

    Route::get('/sms-log', [SmsLogController::class, 'index'])->name('sms-log.index');
    Route::post('/sms-log/recharge', [SmsLogController::class, 'recharge'])->name('sms-log.recharge');
    Route::get('/mail-log', [MailLogController::class, 'index'])->name('mail-log.index');
    Route::get('/data/clear', [DataController::class, 'clearDataForm'])->name('data.clear.form');
    Route::post('/data/clear', [DataController::class, 'clearData'])->name('data.clear');

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

    // Media Library (me_media — files of every package)
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::patch('/media/{media}', [MediaController::class, 'update'])->name('media.update');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::post('/media/{media}/restore', [MediaController::class, 'restore'])->name('media.restore');
    Route::delete('/media/{media}/force', [MediaController::class, 'forceDelete'])->name('media.force-delete');
});


// Serves a media file by uuid; private files need a signed URL (Media::url() makes it)
Route::get('/media/{media}/{conversion?}', [MediaController::class, 'show'])->middleware('web')
    ->where('media', '[0-9a-fA-F-]{36}')->name('me.media.show');

require __DIR__.'/file.php';
require __DIR__.'/auth.php';

Route::get('/favicon.svg', function () { return response(view('me::svg')) ->header('Content-Type', 'image/svg+xml'); })->name('favicon.svg');


