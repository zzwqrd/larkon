<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Admin Routes (Senior Advanced Architecture)
|--------------------------------------------------------------------------
*/

// --- Public Auth Routes ---
Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [
        'uses' => RegisteredUserController::class . '@create',
        'as' => 'register',
        'title' => ['messages.sign_up']
    ]);
    Route::post('/register', [
        'uses' => RegisteredUserController::class . '@store'
    ]);

    Route::get('/login', [
        'uses' => AuthenticatedSessionController::class . '@create',
        'as' => 'login',
        'title' => ['messages.sign_in']
    ]);
    Route::post('/login', [
        'uses' => AuthenticatedSessionController::class . '@store'
    ]);

    Route::get('/forgot-password', [
        'uses' => PasswordResetLinkController::class . '@create',
        'as' => 'password.request',
        'title' => ['messages.reset_password_request']
    ]);
    Route::post('/forgot-password', [
        'uses' => PasswordResetLinkController::class . '@store',
        'as' => 'password.email',
        'title' => ['messages.send_password_reset_link']
    ]);

    Route::get('/reset-password/{token}', [
        'uses' => NewPasswordController::class . '@create',
        'as' => 'password.reset',
        'title' => ['messages.reset_password']
    ]);
    Route::post('/reset-password', [
        'uses' => NewPasswordController::class . '@store',
        'as' => 'password.update',
        'title' => ['messages.update_password']
    ]);
});

// --- Protected Admin Routes ---
Route::group(['middleware' => ['auth', 'permission']], function () {

    // Dashboard
    Route::get('/', [
        'uses' => RoutingController::class . '@index',
        'as' => 'root',
        'title' => ['messages.dashboard'],
        'icon' => 'solar:widget-5-bold-duotone',
        'master' => true
    ]);

    // Logout
    Route::post('/logout', [
        'uses' => AuthenticatedSessionController::class . '@destroy',
        'as' => 'logout',
        'title' => ['messages.logout']
    ]);

    // Verification & Security
    Route::group(['prefix' => 'verification'], function () {
        Route::get('notice', [
            'uses' => EmailVerificationPromptController::class . '@__invoke',
            'as' => 'verification.notice',
            'title' => ['messages.verification_notice']
        ]);
        Route::get('verify/{id}/{hash}', [
            'uses' => VerifyEmailController::class . '@__invoke',
            'as' => 'verification.verify',
            'middleware' => ['signed', 'throttle:6,1'],
            'title' => ['messages.verification_verify']
        ]);
        Route::post('send', [
            'uses' => EmailVerificationNotificationController::class . '@store',
            'as' => 'verification.send',
            'middleware' => ['throttle:6,1'],
            'title' => ['messages.verification_send']
        ]);
        Route::get('confirm', [
            'uses' => ConfirmablePasswordController::class . '@show',
            'as' => 'password.confirm',
            'title' => ['messages.password_confirm']
        ]);
        Route::post('confirm', [
            'uses' => ConfirmablePasswordController::class . '@store'
        ]);
    });

    // Admins Management
    Route::group(['prefix' => 'admin/admins', 'as' => 'admins.'], function () {
        Route::get('list', [
            'uses' => AdminController::class . '@index',
            'as' => 'list',
            'title' => ['messages.admins_list'],
            'icon' => 'solar:user-bold-duotone',
            'master' => true,
            'children' => ['admins.create', 'admins.store', 'admins.edit', 'admins.update', 'admins.destroy', 'admins.bulkDelete']
        ]);
        Route::get('create', ['uses' => AdminController::class . '@create', 'as' => 'create', 'title' => ['messages.add_admin']]);
        Route::post('/', ['uses' => AdminController::class . '@store', 'as' => 'store', 'title' => ['messages.save_admin']]);
        Route::get('{id}/edit', ['uses' => AdminController::class . '@edit', 'as' => 'edit', 'title' => ['messages.edit_admin']]);
        Route::put('{id}', ['uses' => AdminController::class . '@update', 'as' => 'update', 'title' => ['messages.update_admin']]);
        Route::delete('{id}', ['uses' => AdminController::class . '@destroy', 'as' => 'destroy', 'title' => ['messages.delete_admin']]);
        Route::post('bulk-delete', ['uses' => AdminController::class . '@bulkDelete', 'as' => 'bulkDelete', 'title' => ['messages.bulk_delete_admins']]);
    });

    // Roles & Permissions
    Route::group(['prefix' => 'admin/roles', 'as' => 'roles.'], function () {
        Route::get('list', [
            'uses' => RoleController::class . '@index',
            'as' => 'index',
            'title' => ['messages.roles_list'],
            'icon' => 'solar:user-speak-rounded-bold-duotone',
            'master' => true,
            'children' => ['roles.create', 'roles.store', 'roles.edit', 'roles.update', 'roles.destroy', 'roles.bulkDelete']
        ]);
        Route::get('create', ['uses' => RoleController::class . '@create', 'as' => 'create', 'title' => ['messages.create_role']]);
        Route::post('/', ['uses' => RoleController::class . '@store', 'as' => 'store', 'title' => ['messages.save_role']]);
        Route::get('{id}/edit', ['uses' => RoleController::class . '@edit', 'as' => 'edit', 'title' => ['messages.edit_role']]);
        Route::put('{id}', ['uses' => RoleController::class . '@update', 'as' => 'update', 'title' => ['messages.update_role']]);
        Route::delete('{id}', ['uses' => RoleController::class . '@destroy', 'as' => 'destroy', 'title' => ['messages.delete_role']]);
        Route::post('bulk-delete', ['uses' => RoleController::class . '@bulkDelete', 'as' => 'bulkDelete', 'title' => ['messages.bulk_delete_roles']]);
    });

    // Permissions Index
    Route::get('admin/permissions', [
        'uses' => RoleController::class . '@permissionsIndex',
        'as' => 'permissions.index',
        'title' => ['messages.permissions'],
        'icon' => 'solar:shield-keyhole-bold-duotone'
    ]);

    // Products Module
    Route::group(['prefix' => 'general/products', 'as' => 'products.'], function () {
        Route::get('list', [
            'uses' => ProductController::class . '@index',
            'as' => 'index',
            'title' => ['messages.products_list'],
            'icon' => 'solar:t-shirt-bold-duotone',
            'master' => true,
            'children' => ['products.destroy', 'products.bulkDelete']
        ]);
        Route::delete('{id}', ['uses' => ProductController::class . '@destroy', 'as' => 'destroy', 'title' => ['messages.delete_product']]);
        Route::post('bulk-delete', ['uses' => ProductController::class . '@bulkDelete', 'as' => 'bulkDelete', 'title' => ['messages.bulk_delete_products']]);
    });

    // Other Pages (Dynamic Routing)
    Route::get('{first}/{second}/{third}', [
        'uses' => RoutingController::class . '@thirdLevel',
        'as' => 'third',
        'title' => ['messages.other_pages']
    ]);
    Route::get('{first}/{second}', [
        'uses' => RoutingController::class . '@secondLevel',
        'as' => 'second',
        'title' => ['messages.other_pages']
    ]);
    Route::get('{any}', [
        'uses' => RoutingController::class . '@root',
        'as' => 'any',
        'title' => ['messages.other_pages']
    ]);
});
