<?php


use Illuminate\Support\Facades\Route;
use ME\Http\Controllers\Auth\AuthController;

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
    Route::post('/verify-reset-otp', [AuthController::class, 'verifyResetOtp'])->name('password.verify.otp');

    Route::post('logout', [AuthController::class, 'destroy'])->name('logout');

});

