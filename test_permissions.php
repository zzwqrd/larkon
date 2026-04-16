<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
app()->setLocale('ar');
$controller = new App\Http\Controllers\Admin\RoleController();
$reflection = new ReflectionClass(App\Http\Controllers\Admin\RoleController::class);
$method = $reflection->getMethod('getPermissionGroups');
$method->setAccessible(true);
$groups = $method->invoke($controller);
print_r(array_keys($groups));
