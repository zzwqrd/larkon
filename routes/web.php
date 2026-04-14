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
    Route::get('', [RoutingController::class, 'index'])->name('root');

    // Products module (Server-side dynamic routes)
    Route::get('general/products/list', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
    Route::delete('general/products/{id}', [\App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('general/products/bulk-delete', [\App\Http\Controllers\ProductController::class, 'bulkDelete'])->name('products.bulkDelete');

    // Admins module
    Route::get('admin/admins/list', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admins.list');
    Route::get('admin/admins/create', [\App\Http\Controllers\Admin\AdminController::class, 'create'])->name('admins.create');
    Route::post('admin/admins', [\App\Http\Controllers\Admin\AdminController::class, 'store'])->name('admins.store');
    Route::get('admin/admins/{id}/edit', [\App\Http\Controllers\Admin\AdminController::class, 'edit'])->name('admins.edit');
    Route::put('admin/admins/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'update'])->name('admins.update');
    Route::delete('admin/admins/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'destroy'])->name('admins.destroy');
    Route::post('admin/admins/bulk-delete', [\App\Http\Controllers\Admin\AdminController::class, 'bulkDelete'])->name('admins.bulkDelete');

    // Roles module
    Route::get('admin/roles/list', [\App\Http\Controllers\Admin\RoleController::class, 'index'])->name('roles.index');
    Route::get('admin/roles/create', [\App\Http\Controllers\Admin\RoleController::class, 'create'])->name('roles.create');
    Route::post('admin/roles', [\App\Http\Controllers\Admin\RoleController::class, 'store'])->name('roles.store');
    Route::delete('admin/roles/{id}', [\App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('roles.destroy');

    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});
