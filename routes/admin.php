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

/*
|--------------------------------------------------------------------------
| Admin Routes (Senior Advanced Architecture)
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

// --- Protected Admin Routes (Mirroring Perfume_Admin Structure) ---
Route::group(['middleware' => ['admin.auth', 'permission']], function () {

    // --- SECTION: GENERAL ---

    // Dashboard
    Route::get('/', [
        'uses' => RoutingController::class . '@index',
        'as' => 'admin.dashboard.index', // Explicit name for permission redirection
        'title' => ['messages.dashboard'],
        'icon' => 'solar:widget-5-bold-duotone',
        'menu_title' => 'messages.general',
        'master' => true,
    ]);

    // Alias 'root' for compatibility
    Route::get('dashboard', function () {
        return redirect()->route('admin.dashboard.index');
    })->name('root')->middleware('sub_route');


    // Admins Management Menu
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

    // Roles Management Menu
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

    // Permissions Index
    Route::get('admin/permissions', [
        'uses' => RoleController::class . '@permissionsIndex',
        'as' => 'permissions.index',
        'title' => ['messages.permissions'],
        'icon' => 'solar:shield-keyhole-bold-duotone'
    ]);
    Route::post('admin/permissions/update-roles', [
        'uses' => RoleController::class . '@updatePermissionRoles',
        'as' => 'admin.permissions.update-roles',
        'sub_route' => true
    ]);

    // Products Module Menu
    Route::get('general/products-menu', [
        'as' => 'products.menu',
        'type' => 'parent',
        'title' => ['messages.products'],
        'icon' => 'solar:t-shirt-bold-duotone',
        'children' => ['products.index', 'products.grid', 'products.detail', 'products.create']
    ]);
    Route::group(['prefix' => 'general/products', 'as' => 'products.'], function () {
        Route::get('list', ['uses' => ProductController::class . '@index', 'as' => 'index', 'title' => ['messages.list'], 'sub_route' => true]);
        Route::get('grid', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'general', 'second' => 'products', 'third' => 'grid'], 'as' => 'grid', 'title' => ['messages.grid'], 'sub_route' => true]);
        Route::get('detail', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'general', 'second' => 'products', 'third' => 'details'], 'as' => 'detail', 'title' => ['messages.details'], 'sub_route' => true]);
        Route::get('create', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'general', 'second' => 'products', 'third' => 'create'], 'as' => 'create', 'title' => ['messages.create'], 'sub_route' => true]);
        Route::delete('{id}', ['uses' => ProductController::class . '@destroy', 'as' => 'destroy', 'title' => ['messages.delete_product']]);
        Route::post('bulk-delete', ['uses' => ProductController::class . '@bulkDelete', 'as' => 'bulkDelete', 'title' => ['messages.bulk_delete_products']]);
    });

    // Category Menu
    Route::get('general/category-menu', [
        'as' => 'category.menu',
        'type' => 'parent',
        'title' => ['messages.category'],
        'icon' => 'solar:clipboard-list-bold-duotone',
        'children' => ['category.index', 'category.create']
    ]);
    Route::group(['prefix' => 'general/category', 'as' => 'category.'], function () {
        Route::get('list', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'general', 'second' => 'category', 'third' => 'list'], 'as' => 'index', 'title' => ['messages.list'], 'sub_route' => true]);
        Route::get('create', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'general', 'second' => 'category', 'third' => 'create'], 'as' => 'create', 'title' => ['messages.create'], 'sub_route' => true]);
    });

    // Inventory Menu
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

    // Orders Menu
    Route::get('general/orders-menu', [
        'as' => 'orders.menu',
        'type' => 'parent',
        'title' => ['messages.orders'],
        'icon' => 'solar:bag-smile-bold-duotone',
        'children' => ['orders.index', 'orders.details']
    ]);
    Route::group(['prefix' => 'general/orders', 'as' => 'orders.'], function () {
        Route::get('list', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'general', 'second' => 'orders', 'third' => 'list'], 'as' => 'index', 'title' => ['messages.list'], 'sub_route' => true]);
        Route::get('details', ['uses' => RoutingController::class . '@thirdLevel', 'defaults' => ['first' => 'general', 'second' => 'orders', 'third' => 'details'], 'as' => 'details', 'title' => ['messages.details'], 'sub_route' => true]);
    });

    // Settings
    Route::get('general/settings', [
        'uses' => RoutingController::class . '@secondLevel',
        'defaults' => ['first' => 'general', 'second' => 'settings'],
        'as' => 'general.settings',
        'title' => ['messages.settings'],
        'icon' => 'solar:settings-bold-duotone'
    ]);


    // --- SECTION: USERS ---

    // Customers
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

    // Sellers
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


    // --- SECTION: OTHER ---

    // Coupons
    Route::get('other/coupons-menu', [
        'as' => 'coupons.menu',
        'type' => 'parent',
        'title' => ['messages.coupons'],
        'icon' => 'solar:leaf-bold-duotone',
        'menu_title' => 'messages.other',
        'children' => ['coupons.index', 'coupons.add']
    ]);
    Route::group(['prefix' => 'other', 'as' => 'coupons.'], function () {
        Route::get('coupons-list', ['uses' => RoutingController::class . '@secondLevel', 'defaults' => ['first' => 'other', 'second' => 'coupons-list'], 'as' => 'index', 'title' => ['messages.list'], 'sub_route' => true]);
        Route::get('coupons-add', ['uses' => RoutingController::class . '@secondLevel', 'defaults' => ['first' => 'other', 'second' => 'coupons-add'], 'as' => 'add', 'title' => ['messages.add'], 'sub_route' => true]);
    });

    // Reviews
    Route::get('other/reviews', [
        'uses' => RoutingController::class . '@secondLevel',
        'defaults' => ['first' => 'other', 'second' => 'pages-review'],
        'as' => 'other.reviews',
        'title' => ['messages.reviews'],
        'icon' => 'solar:chat-square-like-bold-duotone'
    ]);


    // --- SECTION: SUPPORT ---

    // Help Center
    Route::get('support/help-center', [
        'uses' => RoutingController::class . '@secondLevel',
        'defaults' => ['first' => 'support', 'second' => 'help-center'],
        'as' => 'support.help-center',
        'title' => ['messages.help_center'],
        'icon' => 'solar:help-bold-duotone',
        'menu_title' => 'messages.support'
    ]);

    // FAQs
    Route::get('support/faqs', [
        'uses' => RoutingController::class . '@secondLevel',
        'defaults' => ['first' => 'support', 'second' => 'faqs'],
        'as' => 'support.faqs',
        'title' => ['messages.faqs'],
        'icon' => 'solar:question-circle-bold-duotone'
    ]);


    // --- SECTION: OTHER APPS ---

    // Chat
    Route::get('apps/chat', [
        'uses' => RoutingController::class . '@secondLevel',
        'defaults' => ['first' => 'apps', 'second' => 'chat'],
        'as' => 'apps.chat',
        'title' => ['messages.chat'],
        'icon' => 'solar:chat-round-bold-duotone',
        'menu_title' => 'messages.other_apps'
    ]);

    // Email
    Route::get('apps/email', [
        'uses' => RoutingController::class . '@secondLevel',
        'defaults' => ['first' => 'apps', 'second' => 'email'],
        'as' => 'apps.email',
        'title' => ['messages.email'],
        'icon' => 'solar:mailbox-bold-duotone'
    ]);

    // Calendar
    Route::get('apps/calendar', [
        'uses' => RoutingController::class . '@secondLevel',
        'defaults' => ['first' => 'apps', 'second' => 'calendar'],
        'as' => 'apps.calendar',
        'title' => ['messages.calendar'],
        'icon' => 'solar:calendar-bold-duotone'
    ]);

    // Todo
    Route::get('apps/todo', [
        'uses' => RoutingController::class . '@secondLevel',
        'defaults' => ['first' => 'apps', 'second' => 'todo'],
        'as' => 'apps.todo',
        'title' => ['messages.todo'],
        'icon' => 'solar:checklist-bold-duotone'
    ]);


    // --- SECTION: CUSTOM & PAGES ---

    // Pages Menu
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

    // Widgets
    Route::get('custom/widgets', [
        'uses' => RoutingController::class . '@secondLevel',
        'defaults' => ['first' => 'custom', 'second' => 'widgets'],
        'as' => 'custom.widgets',
        'title' => ['messages.widgets'],
        'icon' => 'solar:atom-bold-duotone'
    ]);


    // --- SECTION: COMPONENTS UI (Grouped) ---
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


    // Hide technical routes from Sidebar but keep active for internal logic
    Route::get('admin/logout', ['uses' => AuthController::class . '@logout', 'as' => 'admin.logout', 'sub_route' => true]);
    Route::get('admin/change-lang/{lang}', ['uses' => AuthController::class . '@changeLnag', 'as' => 'admin.changeLang', 'sub_route' => true]);

    // Generic Dynamic Routing
    Route::get('{first}/{second}/{third}', ['uses' => RoutingController::class . '@thirdLevel', 'as' => 'third', 'sub_route' => true]);
    Route::get('{first}/{second}', ['uses' => RoutingController::class . '@secondLevel', 'as' => 'second', 'sub_route' => true]);
    Route::get('{any}', ['uses' => RoutingController::class . '@root', 'as' => 'any', 'sub_route' => true]);
});
