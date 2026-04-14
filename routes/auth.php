<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::get('/register', [
    'uses' => RegisteredUserController::class . '@create',
    'as' => 'register',
    'middleware' => 'guest',
    'title' => ['messages.sign_up']
]);

Route::post('/register', [
    'uses' => RegisteredUserController::class . '@store',
    'middleware' => 'guest'
]);

Route::get('/login', [
    'uses' => AuthenticatedSessionController::class . '@create',
    'as' => 'login',
    'middleware' => 'guest',
    'title' => ['messages.sign_in']
]);

Route::post('/login', [
    'uses' => AuthenticatedSessionController::class . '@store',
    'middleware' => 'guest'
]);

Route::get('/forgot-password', [
    'uses' => PasswordResetLinkController::class . '@create',
    'as' => 'password.request',
    'middleware' => 'guest',
    'title' => ['messages.reset_password_request']
]);

Route::post('/forgot-password', [
    'uses' => PasswordResetLinkController::class . '@store',
    'as' => 'password.email',
    'middleware' => 'guest',
    'title' => ['messages.send_password_reset_link']
]);

Route::get('/reset-password/{token}', [
    'uses' => NewPasswordController::class . '@create',
    'as' => 'password.reset',
    'middleware' => 'guest',
    'title' => ['messages.reset_password']
]);

Route::post('/reset-password', [
    'uses' => NewPasswordController::class . '@store',
    'as' => 'password.update',
    'middleware' => 'guest',
    'title' => ['messages.update_password']
]);

Route::get('/verify-email', [
    'uses' => EmailVerificationPromptController::class . '@__invoke',
    'as' => 'verification.notice',
    'middleware' => 'auth',
    'title' => ['messages.verification_notice']
]);

Route::get('/verify-email/{id}/{hash}', [
    'uses' => VerifyEmailController::class . '@__invoke',
    'as' => 'verification.verify',
    'middleware' => ['auth', 'signed', 'throttle:6,1'],
    'title' => ['messages.verification_verify']
]);

Route::post('/email/verification-notification', [
    'uses' => EmailVerificationNotificationController::class . '@store',
    'as' => 'verification.send',
    'middleware' => ['auth', 'throttle:6,1'],
    'title' => ['messages.verification_send']
]);

Route::get('/confirm-password', [
    'uses' => ConfirmablePasswordController::class . '@show',
    'as' => 'password.confirm',
    'middleware' => 'auth',
    'title' => ['messages.password_confirm']
]);

Route::post('/confirm-password', [
    'uses' => ConfirmablePasswordController::class . '@store',
    'middleware' => 'auth'
]);

Route::post('/logout', [
    'uses' => AuthenticatedSessionController::class . '@destroy',
    'as' => 'logout',
    'middleware' => 'auth',
    'title' => ['messages.logout']
]);
