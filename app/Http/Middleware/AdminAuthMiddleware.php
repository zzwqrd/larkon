<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (auth('admin')->check()) {
                // Set 'admin' as the default guard for this request so that
                // @can() / Gate checks in admin views use the Admin model,
                // not the default 'web' (User) guard.
                auth()->shouldUse('admin');
                return $next($request);
            }
        } catch (\Exception $e) {
            // If DB connection fails, redirect to login (or could show maintenance page)
        }
        
        // If not authenticated via admin guard, redirect to admin login
        return redirect()->route('admin.show.login');
    }
}
