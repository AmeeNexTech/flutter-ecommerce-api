<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\User\UserController;

  Route::prefix('auth')->group(function () {
      // مسارات إنشاء الحساب والتحقق
      Route::post('/register', [RegisterController::class, 'register']);
      Route::post('/verify-registration-otp', [RegisterController::class, 'verifyOtp']);
      Route::post('/resend-otp', [RegisterController::class, 'resendOtp']);
      // مسار تسجيل الدخول
      Route::post('/login', [LoginController::class, 'login']);
      // مسار إعادة تعيين كلمة المرور
      Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword']);
      Route::post('/verify-reset-otp', [PasswordResetController::class, 'verifyOtp']);
      Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
      Route::post('/resend-reset-otp', [PasswordResetController::class, 'resendResetOtp']);
      // مسار تسجيل الخروج
      Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');
      // مسار حذف الحساب
      Route::post('/delete-account', [UserController::class, 'deleteAccount'])->middleware('auth:sanctum');
    });
