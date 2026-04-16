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
            \App\Http\Middleware\LanguageSwitcher::class,
            \App\Http\Middleware\SecurityShieldMiddleware::class,
            \App\Http\Middleware\XssSanitizer::class,
        ]);

        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\AdminAuthMiddleware::class,
            'check-role' => \App\Http\Middleware\CheckRoleMiddleware::class,
            'permission' => \App\Http\Middleware\CheckRoleMiddleware::class, // Fallback alias
            'AdminReadNotificationMiddleware' => \App\Http\Middleware\AdminReadNotificationMiddleware::class,
            'security.shield' => \App\Http\Middleware\SecurityShieldMiddleware::class,
            'xss.sanitizer' => \App\Http\Middleware\XssSanitizer::class,
            'lang.switcher' => \App\Http\Middleware\LanguageSwitcher::class,
            'admin.read.notifications' => \App\Http\Middleware\AdminReadNotificationMiddleware::class,
            'get.location' => \App\Http\Middleware\GetLocation::class,
            'isAdmin' => \App\Http\Middleware\isAdmin::class,
        ]);

        $middleware->redirectTo(
            guests: function ($request) {
                // If unauthorized and not Ajax, redirect to the admin login page
                return route('admin.show.login');
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
