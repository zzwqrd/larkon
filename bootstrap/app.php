<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\SecurityShieldMiddleware::class,
        ]);
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'admin.auth' => \App\Http\Middleware\AdminAuthMiddleware::class,
        ]);

        $middleware->redirectTo(
            guests: function ($request) {
                if ($request->is('admin/*') || $request->is('admin')) {
                    return route('admin.show.login');
                }
                return route('login');
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
