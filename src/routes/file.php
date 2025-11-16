<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
