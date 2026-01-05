<?php


use Illuminate\Support\Facades\Route;
use ME\Http\Controllers\Auth\AuthController;
use ME\Http\Controllers\Auth\PasswordController;
use ME\Http\Controllers\Auth\NewPasswordController;
use ME\Http\Controllers\Auth\VerifyEmailController;
use ME\Http\Controllers\Auth\RegisteredUserController;
use ME\Http\Controllers\Auth\PasswordResetLinkController;
use ME\Http\Controllers\Auth\ConfirmablePasswordController;
use ME\Http\Controllers\Auth\AuthenticatedSessionController;
use ME\Http\Controllers\Auth\EmailVerificationPromptController;
use ME\Http\Controllers\Auth\EmailVerificationNotificationController;

Route::middleware(['web', 'guest'])->group(function () {
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('register', [AuthController::class, 'registerStore'])->name('register.store');
    Route::post('send-otp', [AuthController::class, 'sendOtp'])->name('otp.send');
    Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->name('otpVerify');

    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'loginStore']);

    Route::get('/forget-password', [AuthController::class, 'forgetPassword'])->name('password.forget');
    Route::post('/forget-password', [AuthController::class, 'forgetPasswordStore'])->name('password.forget.store');
    Route::post('/reset-password', [AuthController::class, 'resetPasswordStore'])->name('password.reset.store');

    // Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    // Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    // Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    // Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
