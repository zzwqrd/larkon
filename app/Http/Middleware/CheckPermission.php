<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Get the current route name
        $routeName = $request->route()->getName();

        // 2. Allow specific routes that don't need permission (like root or logout)
        if (in_array($routeName, ['root', 'lang', 'logout', 'any'])) {
            return $next($request);
        }

        // 3. Get the authenticated admin (assuming user() returns Admin for this guard)
        $user = auth()->user();

        // 4. Super Admin (Role ID 1) has all permissions
        if ($user && $user->role_id == 1) {
            return $next($request);
        }

        // 5. Check if user has the specific permission
        if ($user && $user->role) {
            $permissions = $user->role->permissions->pluck('permission')->toArray();
            
            if (in_array($routeName, $permissions)) {
                return $next($request);
            }
        }

        // 6. Forbidden if no permission
        if ($request->ajax()) {
            return response()->json(['success' => false, 'msg' => 'Unauthorized Access'], 403);
        }

        abort(403, 'You do not have permission to access this page.');
    }
}
