<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang');

require __DIR__ . '/auth.php';

Route::group(['prefix' => '/', 'middleware' => ['auth', 'permission']], function () {
    Route::get('', [
        'uses' => RoutingController::class . '@index',
        'as' => 'root',
        'title' => ['messages.dashboard']
    ]);

    // Products module
    Route::group(['prefix' => 'general/products', 'as' => 'products.'], function () {
        Route::get('list', [
            'uses' => \App\Http\Controllers\ProductController::class . '@index',
            'as' => 'index',
            'title' => ['messages.products_list']
        ]);
        Route::delete('{id}', [
            'uses' => \App\Http\Controllers\ProductController::class . '@destroy',
            'as' => 'destroy',
            'title' => ['messages.delete_product']
        ]);
        Route::post('bulk-delete', [
            'uses' => \App\Http\Controllers\ProductController::class . '@bulkDelete',
            'as' => 'bulkDelete',
            'title' => ['messages.bulk_delete_products']
        ]);
    });

    // Admins module
    Route::group(['prefix' => 'admin/admins', 'as' => 'admins.'], function () {
        Route::get('list', [
            'uses' => \App\Http\Controllers\Admin\AdminController::class . '@index',
            'as' => 'list',
            'title' => ['messages.admins_list']
        ]);
        Route::get('create', [
            'uses' => \App\Http\Controllers\Admin\AdminController::class . '@create',
            'as' => 'create',
            'title' => ['messages.add_admin']
        ]);
        Route::post('/', [
            'uses' => \App\Http\Controllers\Admin\AdminController::class . '@store',
            'as' => 'store',
            'title' => ['messages.save_admin']
        ]);
        Route::get('{id}/edit', [
            'uses' => \App\Http\Controllers\Admin\AdminController::class . '@edit',
            'as' => 'edit',
            'title' => ['messages.edit_admin']
        ]);
        Route::put('{id}', [
            'uses' => \App\Http\Controllers\Admin\AdminController::class . '@update',
            'as' => 'update',
            'title' => ['messages.update_admin']
        ]);
        Route::delete('{id}', [
            'uses' => \App\Http\Controllers\Admin\AdminController::class . '@destroy',
            'as' => 'destroy',
            'title' => ['messages.delete_admin']
        ]);
        Route::post('bulk-delete', [
            'uses' => \App\Http\Controllers\Admin\AdminController::class . '@bulkDelete',
            'as' => 'bulkDelete',
            'title' => ['messages.bulk_delete_admins']
        ]);
    });

    // Roles & Permissions
    Route::group(['prefix' => 'admin/roles', 'as' => 'roles.'], function () {
        Route::get('list', [
            'uses' => \App\Http\Controllers\Admin\RoleController::class . '@index',
            'as' => 'index',
            'title' => ['messages.roles_list']
        ]);
        Route::get('create', [
            'uses' => \App\Http\Controllers\Admin\RoleController::class . '@create',
            'as' => 'create',
            'title' => ['messages.create_role']
        ]);
        Route::post('/', [
            'uses' => \App\Http\Controllers\Admin\RoleController::class . '@store',
            'as' => 'store',
            'title' => ['messages.save_role']
        ]);
        Route::get('{id}/edit', [
            'uses' => \App\Http\Controllers\Admin\RoleController::class . '@edit',
            'as' => 'edit',
            'title' => ['messages.edit_role']
        ]);
        Route::put('{id}', [
            'uses' => \App\Http\Controllers\Admin\RoleController::class . '@update',
            'as' => 'update',
            'title' => ['messages.update_role']
        ]);
        Route::delete('{id}', [
            'uses' => \App\Http\Controllers\Admin\RoleController::class . '@destroy',
            'as' => 'destroy',
            'title' => ['messages.delete_role']
        ]);
        Route::post('bulk-delete', [
            'uses' => \App\Http\Controllers\Admin\RoleController::class . '@bulkDelete',
            'as' => 'bulkDelete',
            'title' => ['messages.bulk_delete_roles']
        ]);
    });

    Route::get('admin/permissions', [
        'uses' => \App\Http\Controllers\Admin\RoleController::class . '@permissionsIndex',
        'as' => 'permissions.index',
        'title' => ['messages.permissions']
    ]);

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
