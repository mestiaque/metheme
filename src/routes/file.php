<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Route::get('/attachments/{filename}', function ($filename) {
    $path = 'images/products/' . $filename;
    if (!Storage::disk('public')->exists($path)) abort(404);
    return response()->file(storage_path('app/public/' . $path));
})->name('attachments.show');

Route::get('/profile/{filename}', function ($filename) {
    $path = 'images/profile_images/' . $filename;
    if (!Storage::disk('public')->exists($path)) abort(404);
    return response()->file(storage_path('app/public/' . $path));
})->name('profile_img.show');

Route::get('/shop-logo/{filename}', function ($filename) {
    $path = 'images/shop_logo/' . $filename;
    if (!Storage::disk('public')->exists($path)) abort(404);
    return response()->file(storage_path('app/public/' . $path));
})->name('shop_logo.show');



Route::get('/app-logo', function () {
    $logo = get_setting('app_logo')
        ? get_image('app_logo')
        : asset('assets/img/favicon/Encodex.ico');

    if (!$logo) abort(404);

    // যদি URL হয় → path এ convert করো
    if (Str::startsWith($logo, ['http://', 'https://'])) {
        $path = str_replace(asset('storage'), storage_path('app/public'), $logo);
    } else {
        $path = $logo;
    }

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->name('app_logo.show');
