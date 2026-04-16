<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Admin Routes (Senior Advanced Architecture) - Mirroring Perfume_Admin
|--------------------------------------------------------------------------
*/

// --- Public Dashboard Auth ---
Route::group(['middleware' => 'guest:admin', 'prefix' => 'admin'], function () {

    Route::get('login', [
        'uses' => AuthController::class . '@showLogin',
        'as' => 'admin.show.login',
        'title' => ['messages.sign_in']
    ]);

    Route::post('login', [
        'uses' => AuthController::class . '@login',
        'as' => 'admin.login'
    ]);

    Route::get('/register', [
        'uses' => RegisteredUserController::class . '@create',
        'as' => 'register',
        'title' => ['messages.sign_up']
    ]);
    Route::post('/register', [
        'uses' => RegisteredUserController::class . '@store'
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

// --- Public Technical Routes (no 'admin.' prefix group needed) ---
Route::get('admin/logout', ['uses' => AuthController::class . '@logout', 'as' => 'admin.logout']);
Route::get('admin/change-lang/{lang}', ['uses' => AuthController::class . '@changeLnag', 'as' => 'admin.changeLang']);

// --- Protected Administrative Area ---
Route::group(['as' => 'admin.', 'middleware' => ['admin.auth', 'check-role', 'AdminReadNotificationMiddleware']], function () {

    // --- 1. DASHBOARD ---
    Route::get('/dashboard', [
        'uses' => DashboardController::class . '@index',
        'as' => 'dashboard.index',
        'title' => ['messages.dashboard'],
        'icon' => 'solar:widget-5-bold-duotone',
        'menu_title' => 'messages.general',
        'master' => true,
    ]);

    Route::get('/', [
        'uses' => function () {
            return redirect()->route('admin.dashboard.index');
        },
        'sub_route' => true
    ])->name('root');

    // --- 2. ADMINS MANAGEMENT ---
    Route::get('admin/admins-menu', [
        'as' => 'admins.menu',
        'type' => 'parent',
        'title' => ['messages.admins'],
        'icon' => 'solar:user-bold-duotone',
        'children' => ['admins.list', 'admins.create']
    ]);
    Route::group(['prefix' => 'admin/admins', 'as' => 'admins.'], function () {
        Route::get('list', ['uses' => AdminController::class . '@index', 'as' => 'list', 'title' => ['messages.admins_list'], 'sub_route' => true]);
        Route::get('create', ['uses' => AdminController::class . '@create', 'as' => 'create', 'title' => ['messages.add_admin'], 'sub_route' => true]);
        Route::post('/', ['uses' => AdminController::class . '@store', 'as' => 'store', 'title' => ['messages.save_admin']]);
        Route::get('{id}/edit', ['uses' => AdminController::class . '@edit', 'as' => 'edit', 'title' => ['messages.edit_admin']]);
        Route::put('{id}', ['uses' => AdminController::class . '@update', 'as' => 'update', 'title' => ['messages.update_admin']]);
        Route::delete('{id}', ['uses' => AdminController::class . '@destroy', 'as' => 'destroy', 'title' => ['messages.delete_admin']]);
        Route::post('bulk-delete', ['uses' => AdminController::class . '@bulkDelete', 'as' => 'bulkDelete', 'title' => ['messages.bulk_delete_admins']]);
    });

    // --- 3. USERS (CUSTOMERS & SELLERS) ---
    Route::get('users/customers-menu', [
        'as' => 'customers.menu',
        'type' => 'parent',
        'title' => ['messages.customers'],
        'icon' => 'solar:users-group-two-rounded-bold-duotone',
        'menu_title' => 'messages.users',
        'children' => ['customers.index', 'customers.details']
    ]);
    Route::group(['prefix' => 'users/customer', 'as' => 'customers.'], function () {
        Route::get('list', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'users', 'second' => 'customer', 'third' => 'list'], 'as' => 'index', 'title' => ['messages.list'], 'sub_route' => true]);
        Route::get('details', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'users', 'second' => 'customer', 'third' => 'details'], 'as' => 'details', 'title' => ['messages.details'], 'sub_route' => true]);
    });

    Route::get('users/sellers-menu', [
        'as' => 'sellers.menu',
        'type' => 'parent',
        'title' => ['messages.sellers'],
        'icon' => 'solar:shop-bold-duotone',
        'children' => ['sellers.index', 'sellers.create']
    ]);
    Route::group(['prefix' => 'users/seller', 'as' => 'sellers.'], function () {
        Route::get('list', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'users', 'second' => 'seller', 'third' => 'list'], 'as' => 'index', 'title' => ['messages.list'], 'sub_route' => true]);
        Route::get('create', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'users', 'second' => 'seller', 'third' => 'create'], 'as' => 'create', 'title' => ['messages.create'], 'sub_route' => true]);
    });

    // --- 4. ROLES MANAGEMENT ---
    Route::get('admin/roles-menu', [
        'as' => 'roles.menu',
        'type' => 'parent',
        'title' => ['messages.roles'],
        'icon' => 'solar:user-speak-rounded-bold-duotone',
        'children' => ['roles.index', 'roles.create']
    ]);
    Route::group(['prefix' => 'admin/roles', 'as' => 'roles.'], function () {
        Route::get('list', ['uses' => RoleController::class . '@index', 'as' => 'index', 'title' => ['messages.roles_list'], 'sub_route' => true]);
        Route::get('create', ['uses' => RoleController::class . '@create', 'as' => 'create', 'title' => ['messages.create_role'], 'sub_route' => true]);
        Route::post('/', ['uses' => RoleController::class . '@store', 'as' => 'store', 'title' => ['messages.save_role']]);
        Route::get('{id}/edit', ['uses' => RoleController::class . '@edit', 'as' => 'edit', 'title' => ['messages.edit_role']]);
        Route::put('{id}', ['uses' => RoleController::class . '@update', 'as' => 'update', 'title' => ['messages.update_role']]);
        Route::delete('{id}', ['uses' => RoleController::class . '@destroy', 'as' => 'destroy', 'title' => ['messages.delete_role']]);
        Route::post('bulk-delete', ['uses' => RoleController::class . '@bulkDelete', 'as' => 'bulkDelete', 'title' => ['messages.bulk_delete_roles']]);
    });

    // --- 5. PERMISSIONS ---
    Route::get('admin/permissions', [
        'uses' => RoleController::class . '@permissionsIndex',
        'as' => 'permissions.index',
        'title' => ['messages.permissions'],
        'icon' => 'solar:shield-keyhole-bold-duotone'
    ]);
    Route::post('admin/permissions/update-roles', [
        'uses' => RoleController::class . '@updatePermissionRoles',
        'as' => 'permissions.update-roles',
        'sub_route' => true
    ]);

    // --- 6. PERFUME MODULES (CORE) ---
    Route::get('general/brand-menu', [
        'as' => 'brands.menu',
        'type' => 'parent',
        'title' => ['messages.brands'],
        'icon' => 'solar:shop-bold-duotone',
        'menu_title' => 'messages.products',
        'children' => ['brands.index', 'brands.create']
    ]);
    Route::group(['prefix' => 'general/brands', 'as' => 'brands.'], function () {
        Route::get('list', ['uses' => \App\Http\Controllers\Admin\Perfume\BrandController::class . '@index', 'as' => 'index', 'title' => ['messages.list'], 'sub_route' => true]);
        Route::get('create', ['uses' => \App\Http\Controllers\Admin\Perfume\BrandController::class . '@create', 'as' => 'create', 'title' => ['messages.create'], 'sub_route' => true]);
        Route::post('store', ['uses' => \App\Http\Controllers\Admin\Perfume\BrandController::class . '@store', 'as' => 'store']);
        Route::get('{id}/edit', ['uses' => \App\Http\Controllers\Admin\Perfume\BrandController::class . '@edit', 'as' => 'edit']);
        Route::put('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\BrandController::class . '@update', 'as' => 'update']);
        Route::delete('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\BrandController::class . '@destroy', 'as' => 'destroy']);
        Route::post('{id}/toggle', ['uses' => \App\Http\Controllers\Admin\Perfume\BrandController::class . '@toggle', 'as' => 'toggle']);
        Route::post('bulk-delete', ['uses' => \App\Http\Controllers\Admin\Perfume\BrandController::class . '@bulkDelete', 'as' => 'bulkDelete']);
    });

    Route::get('general/category-menu', [
        'as' => 'category.menu',
        'type' => 'parent',
        'title' => ['messages.category'],
        'icon' => 'solar:clipboard-list-bold-duotone',
        'children' => ['category.index', 'category.create']
    ]);
    Route::group(['prefix' => 'general/category', 'as' => 'category.'], function () {
        Route::get('list', ['uses' => \App\Http\Controllers\Admin\Perfume\CategoryController::class . '@index', 'as' => 'index', 'title' => ['messages.list'], 'sub_route' => true]);
        Route::get('create', ['uses' => \App\Http\Controllers\Admin\Perfume\CategoryController::class . '@create', 'as' => 'create', 'title' => ['messages.create'], 'sub_route' => true]);
        Route::post('store', ['uses' => \App\Http\Controllers\Admin\Perfume\CategoryController::class . '@store', 'as' => 'store']);
        Route::get('{id}/edit', ['uses' => \App\Http\Controllers\Admin\Perfume\CategoryController::class . '@edit', 'as' => 'edit']);
        Route::put('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\CategoryController::class . '@update', 'as' => 'update']);
        Route::delete('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\CategoryController::class . '@destroy', 'as' => 'destroy']);
        Route::post('{id}/toggle', ['uses' => \App\Http\Controllers\Admin\Perfume\CategoryController::class . '@toggle', 'as' => 'toggle']);
    });

    Route::get('general/products-menu', [
        'as' => 'products.menu',
        'type' => 'parent',
        'title' => ['messages.products'],
        'icon' => 'solar:t-shirt-bold-duotone',
        'children' => ['products.index', 'products.grid', 'products.detail', 'products.create']
    ]);
    Route::group(['prefix' => 'general/products', 'as' => 'products.'], function () {
        Route::get('list', ['uses' => \App\Http\Controllers\Admin\Perfume\ProductController::class . '@index', 'as' => 'index', 'title' => ['messages.list'], 'sub_route' => true]);
        Route::get('create', ['uses' => \App\Http\Controllers\Admin\Perfume\ProductController::class . '@create', 'as' => 'create', 'title' => ['messages.create'], 'sub_route' => true]);
        Route::post('store', ['uses' => \App\Http\Controllers\Admin\Perfume\ProductController::class . '@store', 'as' => 'store']);
        Route::get('{id}/edit', ['uses' => \App\Http\Controllers\Admin\Perfume\ProductController::class . '@edit', 'as' => 'edit']);
        Route::put('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\ProductController::class . '@update', 'as' => 'update']);
        Route::delete('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\ProductController::class . '@destroy', 'as' => 'destroy']);
        Route::post('bulk-delete', ['uses' => \App\Http\Controllers\Admin\Perfume\ProductController::class . '@bulkDelete', 'as' => 'bulkDelete', 'title' => ['messages.bulk_delete_products']]);
        Route::get('grid', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'general', 'second' => 'products', 'third' => 'grid'], 'as' => 'grid', 'title' => ['messages.grid'], 'sub_route' => true]);
        Route::get('detail', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'general', 'second' => 'products', 'third' => 'details'], 'as' => 'detail', 'title' => ['messages.details'], 'sub_route' => true]);
    });

    // Attributes
    Route::get('general/sizes-menu', [
        'as' => 'sizes.menu',
        'type' => 'parent',
        'title' => ['messages.sizes'],
        'icon' => 'solar:ruler-bold-duotone',
        'children' => ['sizes.index', 'sizes.create']
    ]);
    Route::group(['prefix' => 'general/sizes', 'as' => 'sizes.'], function () {
        Route::get('list', ['uses' => \App\Http\Controllers\Admin\Perfume\SizeController::class . '@index', 'as' => 'index', 'title' => ['messages.sizes'], 'sub_route' => true]);
        Route::get('create', ['uses' => \App\Http\Controllers\Admin\Perfume\SizeController::class . '@create', 'as' => 'create', 'sub_route' => true]);
        Route::post('store', ['uses' => \App\Http\Controllers\Admin\Perfume\SizeController::class . '@store', 'as' => 'store']);
        Route::get('{id}/edit', ['uses' => \App\Http\Controllers\Admin\Perfume\SizeController::class . '@edit', 'as' => 'edit']);
        Route::put('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\SizeController::class . '@update', 'as' => 'update']);
        Route::delete('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\SizeController::class . '@destroy', 'as' => 'destroy']);
        Route::post('{id}/toggle', ['uses' => \App\Http\Controllers\Admin\Perfume\SizeController::class . '@toggle', 'as' => 'toggle']);
        Route::post('bulk-delete', ['uses' => \App\Http\Controllers\Admin\Perfume\SizeController::class . '@bulkDelete', 'as' => 'bulkDelete']);
    });

    Route::get('general/notes-menu', [
        'as' => 'notes.menu',
        'type' => 'parent',
        'title' => ['messages.notes'],
        'icon' => 'solar:notes-bold-duotone',
        'children' => ['notes.index', 'notes.create']
    ]);
    Route::group(['prefix' => 'general/notes', 'as' => 'notes.'], function () {
        Route::get('list', ['uses' => \App\Http\Controllers\Admin\Perfume\NoteController::class . '@index', 'as' => 'index', 'title' => ['messages.notes'], 'sub_route' => true]);
        Route::get('create', ['uses' => \App\Http\Controllers\Admin\Perfume\NoteController::class . '@create', 'as' => 'create', 'sub_route' => true]);
        Route::post('store', ['uses' => \App\Http\Controllers\Admin\Perfume\NoteController::class . '@store', 'as' => 'store']);
        Route::get('{id}/edit', ['uses' => \App\Http\Controllers\Admin\Perfume\NoteController::class . '@edit', 'as' => 'edit']);
        Route::put('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\NoteController::class . '@update', 'as' => 'update']);
        Route::delete('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\NoteController::class . '@destroy', 'as' => 'destroy']);
        Route::post('{id}/toggle', ['uses' => \App\Http\Controllers\Admin\Perfume\NoteController::class . '@toggle', 'as' => 'toggle']);
        Route::post('bulk-delete', ['uses' => \App\Http\Controllers\Admin\Perfume\NoteController::class . '@bulkDelete', 'as' => 'bulkDelete']);
    });

    // --- 7. MANUFACTURING ---
    Route::get('manufacturing/formulas-menu', [
        'as' => 'formulas.menu',
        'type' => 'parent',
        'title' => ['messages.formulas'],
        'icon' => 'solar:flask-bold-duotone',
        'menu_title' => 'messages.manufacturing',
        'children' => ['formulas.index', 'formulas.create']
    ]);
    Route::group(['prefix' => 'manufacturing/formulas', 'as' => 'formulas.'], function () {
        Route::get('list', ['uses' => \App\Http\Controllers\Admin\Perfume\FormulaController::class . '@index', 'as' => 'index', 'title' => ['messages.formulas'], 'sub_route' => true]);
        Route::get('create', ['uses' => \App\Http\Controllers\Admin\Perfume\FormulaController::class . '@create', 'as' => 'create', 'sub_route' => true]);
        Route::post('store', ['uses' => \App\Http\Controllers\Admin\Perfume\FormulaController::class . '@store', 'as' => 'store']);
        Route::get('{id}/edit', ['uses' => \App\Http\Controllers\Admin\Perfume\FormulaController::class . '@edit', 'as' => 'edit']);
        Route::put('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\FormulaController::class . '@update', 'as' => 'update']);
        Route::delete('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\FormulaController::class . '@destroy', 'as' => 'destroy']);
        Route::post('{id}/toggle', ['uses' => \App\Http\Controllers\Admin\Perfume\FormulaController::class . '@toggle', 'as' => 'toggle']);
        Route::post('bulk-delete', ['uses' => \App\Http\Controllers\Admin\Perfume\FormulaController::class . '@bulkDelete', 'as' => 'bulkDelete']);
    });

    Route::get('manufacturing/essential-oils-menu', [
        'as' => 'essential-oils.menu',
        'type' => 'parent',
        'title' => ['messages.essential_oils'],
        'icon' => 'solar:test-tube-bold-duotone',
        'children' => ['essential-oils.index', 'essential-oils.create']
    ]);
    Route::group(['prefix' => 'manufacturing/essential-oils', 'as' => 'essential-oils.'], function () {
        Route::get('list', ['uses' => \App\Http\Controllers\Admin\Perfume\EssentialOilController::class . '@index', 'as' => 'index', 'title' => ['messages.essential_oils'], 'sub_route' => true]);
        Route::get('create', ['uses' => \App\Http\Controllers\Admin\Perfume\EssentialOilController::class . '@create', 'as' => 'create', 'sub_route' => true]);
        Route::post('store', ['uses' => \App\Http\Controllers\Admin\Perfume\EssentialOilController::class . '@store', 'as' => 'store']);
        Route::get('{id}/edit', ['uses' => \App\Http\Controllers\Admin\Perfume\EssentialOilController::class . '@edit', 'as' => 'edit']);
        Route::put('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\EssentialOilController::class . '@update', 'as' => 'update']);
        Route::delete('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\EssentialOilController::class . '@destroy', 'as' => 'destroy']);
        Route::post('{id}/toggle', ['uses' => \App\Http\Controllers\Admin\Perfume\EssentialOilController::class . '@toggle', 'as' => 'toggle']);
        Route::post('bulk-delete', ['uses' => \App\Http\Controllers\Admin\Perfume\EssentialOilController::class . '@bulkDelete', 'as' => 'bulkDelete']);
    });

    Route::get('manufacturing/oil-categories-menu', [
        'as' => 'oil-categories.menu',
        'type' => 'parent',
        'title' => ['messages.oil_categories'],
        'icon' => 'solar:folder-bold-duotone',
        'children' => ['oil-categories.index', 'oil-categories.create']
    ]);
    Route::group(['prefix' => 'manufacturing/oil-categories', 'as' => 'oil-categories.'], function () {
        Route::get('list', ['uses' => \App\Http\Controllers\Admin\Perfume\OilCategoryController::class . '@index', 'as' => 'index', 'title' => ['messages.oil_categories'], 'sub_route' => true]);
        Route::get('create', ['uses' => \App\Http\Controllers\Admin\Perfume\OilCategoryController::class . '@create', 'as' => 'create', 'sub_route' => true]);
        Route::post('store', ['uses' => \App\Http\Controllers\Admin\Perfume\OilCategoryController::class . '@store', 'as' => 'store']);
        Route::get('{id}/edit', ['uses' => \App\Http\Controllers\Admin\Perfume\OilCategoryController::class . '@edit', 'as' => 'edit']);
        Route::put('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\OilCategoryController::class . '@update', 'as' => 'update']);
        Route::delete('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\OilCategoryController::class . '@destroy', 'as' => 'destroy']);
        Route::post('{id}/toggle', ['uses' => \App\Http\Controllers\Admin\Perfume\OilCategoryController::class . '@toggle', 'as' => 'toggle']);
        Route::post('bulk-delete', ['uses' => \App\Http\Controllers\Admin\Perfume\OilCategoryController::class . '@bulkDelete', 'as' => 'bulkDelete']);
    });

    // --- 8. ORDERS & INVENTORY ---
    Route::get('general/orders-menu', [
        'as' => 'orders.menu',
        'type' => 'parent',
        'title' => ['messages.orders'],
        'icon' => 'solar:bag-smile-bold-duotone',
        'menu_title' => 'messages.orders',
        'children' => ['orders.index', 'orders.details']
    ]);
    Route::group(['prefix' => 'general/orders', 'as' => 'orders.'], function () {
        Route::get('list', ['uses' => \App\Http\Controllers\Admin\Perfume\OrderController::class . '@index', 'as' => 'index', 'title' => ['messages.list'], 'sub_route' => true]);
        Route::get('{id}/details', ['uses' => \App\Http\Controllers\Admin\Perfume\OrderController::class . '@show', 'as' => 'details', 'title' => ['messages.details'], 'sub_route' => true]);
        Route::post('{id}/status', ['uses' => \App\Http\Controllers\Admin\Perfume\OrderController::class . '@updateStatus', 'as' => 'updateStatus']);
        Route::delete('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\OrderController::class . '@destroy', 'as' => 'destroy']);
    });

    Route::get('general/inventory-menu', [
        'as' => 'inventory.menu',
        'type' => 'parent',
        'title' => ['messages.inventory'],
        'icon' => 'solar:box-bold-duotone',
        'children' => ['inventory.warehouse', 'inventory.received']
    ]);
    Route::group(['prefix' => 'general/inventory', 'as' => 'inventory.'], function () {
        Route::get('warehouse', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'general', 'second' => 'inventory', 'third' => 'warehouse'], 'as' => 'warehouse', 'title' => ['messages.warehouse'], 'sub_route' => true]);
        Route::get('received-orders', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'general', 'second' => 'inventory', 'third' => 'received-orders'], 'as' => 'received', 'title' => ['messages.received_orders'], 'sub_route' => true]);
    });

    // --- 9. LOGISTICS & FEEDBACK ---
    Route::get('logistics/shipping-menu', [
        'as' => 'shipping.menu',
        'type' => 'parent',
        'title' => ['messages.shipping_methods'],
        'icon' => 'solar:truck-bold-duotone',
        'menu_title' => 'messages.logistics',
        'children' => ['shipping.index', 'shipping.create']
    ]);
    Route::group(['prefix' => 'logistics/shipping', 'as' => 'shipping.'], function () {
        Route::get('list', ['uses' => \App\Http\Controllers\Admin\Perfume\ShippingController::class . '@index', 'as' => 'index', 'title' => ['messages.shipping_methods'], 'sub_route' => true]);
        Route::get('create', ['uses' => \App\Http\Controllers\Admin\Perfume\ShippingController::class . '@create', 'as' => 'create', 'sub_route' => true]);
        Route::post('store', ['uses' => \App\Http\Controllers\Admin\Perfume\ShippingController::class . '@store', 'as' => 'store']);
        Route::get('{id}/edit', ['uses' => \App\Http\Controllers\Admin\Perfume\ShippingController::class . '@edit', 'as' => 'edit']);
        Route::put('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\ShippingController::class . '@update', 'as' => 'update']);
        Route::delete('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\ShippingController::class . '@destroy', 'as' => 'destroy']);
        Route::post('{id}/toggle', ['uses' => \App\Http\Controllers\Admin\Perfume\ShippingController::class . '@toggle', 'as' => 'toggle']);
        Route::post('bulk-delete', ['uses' => \App\Http\Controllers\Admin\Perfume\ShippingController::class . '@bulkDelete', 'as' => 'bulkDelete']);
    });

    Route::get('logistics/returns-menu', [
        'as' => 'returns.menu',
        'type' => 'parent',
        'title' => ['messages.return_requests'],
        'icon' => 'solar:undo-left-round-bold-duotone',
        'children' => ['returns.index']
    ]);
    Route::group(['prefix' => 'logistics/returns', 'as' => 'returns.'], function () {
        Route::get('list', ['uses' => \App\Http\Controllers\Admin\Perfume\ReturnRequestController::class . '@index', 'as' => 'index', 'title' => ['messages.return_requests'], 'sub_route' => true]);
        Route::get('{id}/show', ['uses' => \App\Http\Controllers\Admin\Perfume\ReturnRequestController::class . '@show', 'as' => 'show', 'sub_route' => true]);
        Route::post('{id}/status', ['uses' => \App\Http\Controllers\Admin\Perfume\ReturnRequestController::class . '@updateStatus', 'as' => 'updateStatus']);
        Route::delete('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\ReturnRequestController::class . '@destroy', 'as' => 'destroy']);
        Route::post('bulk-delete', ['uses' => \App\Http\Controllers\Admin\Perfume\ReturnRequestController::class . '@bulkDelete', 'as' => 'bulkDelete']);
    });

    Route::get('feedback/reviews-menu', [
        'as' => 'reviews.menu',
        'type' => 'parent',
        'title' => ['messages.reviews'],
        'icon' => 'solar:chat-square-like-bold-duotone',
        'children' => ['reviews.index']
    ]);
    Route::group(['prefix' => 'feedback/reviews', 'as' => 'reviews.'], function () {
        Route::get('list', ['uses' => \App\Http\Controllers\Admin\Perfume\ReviewController::class . '@index', 'as' => 'index', 'title' => ['messages.reviews'], 'sub_route' => true]);
        Route::get('{id}/show', ['uses' => \App\Http\Controllers\Admin\Perfume\ReviewController::class . '@show', 'as' => 'show', 'sub_route' => true]);
        Route::post('{id}/approve', ['uses' => \App\Http\Controllers\Admin\Perfume\ReviewController::class . '@approve', 'as' => 'approve']);
        Route::post('{id}/reject', ['uses' => \App\Http\Controllers\Admin\Perfume\ReviewController::class . '@reject', 'as' => 'reject']);
        Route::delete('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\ReviewController::class . '@destroy', 'as' => 'destroy']);
        Route::post('bulk-delete', ['uses' => \App\Http\Controllers\Admin\Perfume\ReviewController::class . '@bulkDelete', 'as' => 'bulkDelete']);
    });

    // --- 10. OTHER & SUPPORT ---
    Route::get('other/coupons-menu', [
        'as' => 'coupons.menu',
        'type' => 'parent',
        'title' => ['messages.coupons'],
        'icon' => 'solar:leaf-bold-duotone',
        'menu_title' => 'messages.other',
        'children' => ['coupons.index', 'coupons.create']
    ]);
    Route::group(['prefix' => 'other/coupons', 'as' => 'coupons.'], function () {
        Route::get('list', ['uses' => \App\Http\Controllers\Admin\Perfume\CouponController::class . '@index', 'as' => 'index', 'title' => ['messages.list'], 'sub_route' => true]);
        Route::get('create', ['uses' => \App\Http\Controllers\Admin\Perfume\CouponController::class . '@create', 'as' => 'create', 'title' => ['messages.create'], 'sub_route' => true]);
        Route::post('store', ['uses' => \App\Http\Controllers\Admin\Perfume\CouponController::class . '@store', 'as' => 'store']);
        Route::get('{id}/edit', ['uses' => \App\Http\Controllers\Admin\Perfume\CouponController::class . '@edit', 'as' => 'edit']);
        Route::put('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\CouponController::class . '@update', 'as' => 'update']);
        Route::delete('{id}', ['uses' => \App\Http\Controllers\Admin\Perfume\CouponController::class . '@destroy', 'as' => 'destroy']);
        Route::post('{id}/toggle', ['uses' => \App\Http\Controllers\Admin\Perfume\CouponController::class . '@toggle', 'as' => 'toggle']);
        Route::post('bulk-delete', ['uses' => \App\Http\Controllers\Admin\Perfume\CouponController::class . '@bulkDelete', 'as' => 'bulkDelete']);
    });

    Route::get('general/settings', [
        'uses' => \App\Http\Controllers\Admin\Perfume\WebsiteSettingController::class . '@index',
        'as' => 'general.settings',
        'title' => ['messages.settings'],
        'icon' => 'solar:settings-bold-duotone'
    ]);
    Route::post('general/settings', [
        'uses' => \App\Http\Controllers\Admin\Perfume\WebsiteSettingController::class . '@update',
        'as' => 'general.settings.update'
    ]);

    Route::get('support/help-center', [
        'uses' => RoutingController::class . '@secondLevel',
        'defaults' => ['first' => 'support', 'second' => 'help-center'],
        'as' => 'support.help-center',
        'title' => ['messages.help_center'],
        'icon' => 'solar:help-bold-duotone',
        'menu_title' => 'messages.support'
    ]);
    Route::get('support/faqs', [
        'uses' => RoutingController::class . '@secondLevel',
        'defaults' => ['first' => 'support', 'second' => 'faqs'],
        'as' => 'support.faqs',
        'title' => ['messages.faqs'],
        'icon' => 'solar:question-circle-bold-duotone'
    ]);

    // --- 11. APPS ---
    Route::get('apps/chat', [
        'uses' => RoutingController::class . '@secondLevel',
        'defaults' => ['first' => 'apps', 'second' => 'chat'],
        'as' => 'apps.chat',
        'title' => ['messages.chat'],
        'icon' => 'solar:chat-round-bold-duotone',
        'menu_title' => 'messages.other_apps'
    ]);
    Route::get('apps/email', [
        'uses' => RoutingController::class . '@secondLevel',
        'defaults' => ['first' => 'apps', 'second' => 'email'],
        'as' => 'apps.email',
        'title' => ['messages.email'],
        'icon' => 'solar:mailbox-bold-duotone'
    ]);
    Route::get('apps/calendar', [
        'uses' => RoutingController::class . '@secondLevel',
        'defaults' => ['first' => 'apps', 'second' => 'calendar'],
        'as' => 'apps.calendar',
        'title' => ['messages.calendar'],
        'icon' => 'solar:calendar-bold-duotone'
    ]);
    Route::get('apps/todo', [
        'uses' => RoutingController::class . '@secondLevel',
        'defaults' => ['first' => 'apps', 'second' => 'todo'],
        'as' => 'apps.todo',
        'title' => ['messages.todo'],
        'icon' => 'solar:checklist-bold-duotone'
    ]);

    // --- 12. COMPONENTS & CUSTOM ---
    Route::get('custom/pages-menu', [
        'as' => 'pages.menu',
        'type' => 'parent',
        'title' => ['messages.pages'],
        'icon' => 'solar:gift-bold-duotone',
        'menu_title' => 'messages.custom',
        'children' => ['pages.starter', 'pages.pricing', 'pages.maintenance']
    ]);
    Route::group(['prefix' => 'custom/pages', 'as' => 'pages.'], function () {
        Route::get('starter', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'custom', 'second' => 'pages', 'third' => 'starter'], 'as' => 'starter', 'title' => ['messages.welcome_page'], 'sub_route' => true]);
        Route::get('pricing', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'custom', 'second' => 'pages', 'third' => 'pricing'], 'as' => 'pricing', 'title' => ['messages.pricing'], 'sub_route' => true]);
        Route::get('maintenance', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'custom', 'second' => 'pages', 'third' => 'maintenance'], 'as' => 'maintenance', 'title' => ['messages.maintenance'], 'sub_route' => true]);
    });

    Route::get('custom/widgets', [
        'uses' => RoutingController::class . '@secondLevel',
        'defaults' => ['first' => 'custom', 'second' => 'widgets'],
        'as' => 'custom.widgets',
        'title' => ['messages.widgets'],
        'icon' => 'solar:atom-bold-duotone'
    ]);

    Route::get('components/ui-menu', [
        'as' => 'ui.menu',
        'type' => 'parent',
        'title' => ['messages.components_ui'],
        'icon' => 'solar:bookmark-square-bold-duotone',
        'menu_title' => 'messages.components_ui',
        'children' => ['ui.base', 'ui.charts', 'ui.forms', 'ui.tables']
    ]);
    Route::group(['prefix' => 'components', 'as' => 'ui.'], function () {
        Route::get('base-ui', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'components_ui', 'second' => 'ui', 'third' => 'accordion'], 'as' => 'base', 'title' => ['messages.base_ui'], 'sub_route' => true]);
        Route::get('charts', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'components_ui', 'second' => 'charts', 'third' => 'apex-area'], 'as' => 'charts', 'title' => ['messages.charts'], 'sub_route' => true]);
        Route::get('forms', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'components_ui', 'second' => 'forms', 'third' => 'basic'], 'as' => 'forms', 'title' => ['messages.forms'], 'sub_route' => true]);
        Route::get('tables', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'components_ui', 'second' => 'tables', 'third' => 'basic'], 'as' => 'tables', 'title' => ['messages.tables'], 'sub_route' => true]);
    });

});

// --- Dynamic Catch-all Routes (Naming Compatibility Layer) ---
Route::group(['middleware' => ['admin.auth', 'check-role', 'AdminReadNotificationMiddleware']], function () {
    // Generic Dynamic Routing
    Route::get('{first}/{second}/{third}', ['uses' => RoutingController::class . '@thirdLevel', 'as' => 'third', 'sub_route' => true]);
    Route::get('{first}/{second}', ['uses' => RoutingController::class . '@secondLevel', 'as' => 'second', 'sub_route' => true]);
    Route::get('{any}', ['uses' => RoutingController::class . '@root', 'as' => 'any', 'sub_route' => true]);
});
