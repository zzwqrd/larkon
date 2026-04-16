<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;

class CheckRoleMiddleware
{
    use ResponseTrait;

    /**
     * Check if the admin has the required permission for the current route.
     * Ported from perfume_admin with standardized redirects.
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Skip check if it's the dashboard home or a route without a name
        $routeName = $request->route()->getName();
        if (!$routeName || $routeName === 'admin.dashboard.index') {
            return $next($request);
        }

        // 2. Automated permission check logic
        if (auth('admin')->check()) {
            $user = auth('admin')->user();

            // Allow Super Admins (Role ID 1) access to everything
            if ($user->role_id == 1) {
                return $next($request);
            }

            // Check if route name exists in user's role permissions
            $hasPermission = $user->role->permissions()->where('permission', $routeName)->exists();

            if ($hasPermission) {
                return $next($request);
            }

            // Redirection logic for unauthorized access
            if ($request->ajax()) {
                return $this->unauthorizedReturn(['routeName' => $routeName]);
            }

            return redirect()->route('admin.dashboard.index')
                ->with('danger', __('admin.dosenot_hav_permission') . ' (' . $routeName . ')');
        }

        return redirect()->route('admin.login');
    }
}
