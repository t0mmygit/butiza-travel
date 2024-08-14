<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\UserController;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

// Route::get('login/partner', [PartnerAuthenticationController::class, 'create'])
//             ->name('partner-login.create');

// Route::post('login/partner', [PartnerAuthenticationController::class, 'store'])
//             ->name('partner-login.store');

Route::middleware('guest')->group(function () {
    Route::post('register', [RegisteredUserController::class, 'store'])
                ->name('register');

    Route::post('email/validate', ValidateEmailController::class)
                ->name('email.validate');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('login/partner', [PartnerAuthenticationController::class, 'create'])
                ->name('partner-login.create');

    Route::post('login/partner', [PartnerAuthenticationController::class, 'store'])
                ->name('partner-login.store');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');

    Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
                ->name('auth.redirect');

    Route::get('/auth/github/callback', [SocialiteController::class, 'githubCallback'])
                ->name('auth.github.callback');

});

Route::middleware('auth')->group(function () {
    Route::patch('user/update/{user}', [UserController::class, 'update'])
                ->name('user.update');

    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])
                ->name('password.update');

    Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});