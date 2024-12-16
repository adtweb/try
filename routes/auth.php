<?php

use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use Laravel\Fortify\RoutePath;

$verificationLimiter = config('fortify.limiters.verification', '6,1');

Route::get('register', [RegistrationController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::post(RoutePath::for('logout', '/logout'), [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Route::post('register/organization', [RegistrationController::class, 'registerOrganization'])
//     ->middleware(['guest', 'precognitive'])
//     ->name('register.organization');

Route::post('register/participant', [RegistrationController::class, 'registerParticipant'])
    ->middleware(['guest', 'precognitive'])
    ->name('register.participant');

// Password Reset...
Route::get(RoutePath::for('password.request', '/forgot-password'), [PasswordResetLinkController::class, 'create'])
    ->middleware(['guest:'.config('fortify.guard')])
    ->name('password.request');

Route::get(RoutePath::for('password.reset', '/reset-password/{token}'), [NewPasswordController::class, 'create'])
    ->middleware(['guest:'.config('fortify.guard')])
    ->name('password.reset');

Route::post(RoutePath::for('password.email', '/forgot-password'), [PasswordResetLinkController::class, 'store'])
    ->middleware(['guest:'.config('fortify.guard')])
    ->name('password.email');

Route::post(RoutePath::for('password.update', '/reset-password'), [NewPasswordController::class, 'store'])
    ->middleware(['guest:'.config('fortify.guard')])
    ->name('password.update');

// Email Verification...
Route::get(RoutePath::for('verification.notice', '/email/verify'), [EmailVerificationPromptController::class, '__invoke'])
    ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
    ->name('verification.notice');

Route::get(RoutePath::for('verification.verify', '/email/verify/{id}/{hash}'), [VerifyEmailController::class, '__invoke'])
    ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'), 'signed', 'throttle:'.$verificationLimiter])
    ->name('verification.verify');

Route::post(RoutePath::for('verification.send', '/email/verification-notification'), [EmailVerificationNotificationController::class, 'store'])
    ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'), 'throttle:'.$verificationLimiter])
    ->name('verification.send');
