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
        try {
            // 1. Get the current route name
            $routeName = $request->route()->getName();

            // 2. Allow specific routes that don't need permission 
            $allowedRoutes = ['root', 'lang', 'logout', 'any', 'admin.show.login', 'admin.login', 'admin.logout', 'admin.changeLang'];
            if (in_array($routeName, $allowedRoutes)) {
                return $next($request);
            }

            // 3. Get the authenticated admin via the explicit admin guard
            $admin = auth()->guard('admin')->user();

            if (!$admin) {
                return $this->handleUnauthorized($request);
            }

            // 4. Super Admin (Role ID 1) has all permissions
            if ($admin->role_id == 1) {
                return $next($request);
            }

            // 5. Check if user has the specific permission dynamically
            if ($admin->role) {
                $permissions = $admin->role->permissions->pluck('permission')->toArray();
                
                // If they have no permissions, log them out for safety
                if (empty($permissions)) {
                    auth()->guard('admin')->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('admin.show.login')->withErrors(['email' => __('messages.no_permissions')]);
                }

                if (in_array($routeName, $permissions)) {
                    return $next($request);
                }
            }

        } catch (\Exception $e) {
            // Safe fallback if DB is down during permission check
            if ($request->ajax()) {
                return response()->json(['success' => false, 'msg' => 'Database Connection Error'], 500);
            }
            return redirect()->back()->with(['danger' => 'Database Connection Error. Please try again.']);
        }

        // 6. Forbidden if no permission
        return $this->handleUnauthorized($request);
    }

    /**
     * Centralized response handler for unauthorized access
     */
    protected function handleUnauthorized(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => false, 
                'msg' => __('messages.unauthorized_access') ?? 'Unauthorized Access'
            ], 403);
        }

        abort(403, 'You do not have permission to access this page.');
    }
}
