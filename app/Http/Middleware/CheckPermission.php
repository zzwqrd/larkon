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

            // 2. Allow specific white-listed routes
            $allowedRoutes = [
                'admin.show.login', 
                'admin.login', 
                'admin.logout', 
                'admin.changeLang',
                'admin.dashboard.index', // Dashboard is usually allowed for all logged-in admins
                'root'
            ];
            
            if (in_array($routeName, $allowedRoutes)) {
                return $next($request);
            }

            // 3. Get the authenticated admin
            $admin = auth()->guard('admin')->user();
            if (!$admin) {
                return $this->handleUnauthorized($request);
            }

            // 4. Super Admin (Role ID 1) has all permissions
            if ($admin->role_id == 1) {
                return $next($request);
            }

            // 5. Check permissions
            if ($admin->role) {
                $permissions = $admin->role->permissions->pluck('permission')->toArray();
                
                // If they have no permissions, log them out
                if (empty($permissions)) {
                    auth()->guard('admin')->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('admin.show.login')->withErrors(['email' => __('admin.no_permissions')]);
                }

                if (in_array($routeName, $permissions)) {
                    return $next($request);
                }
            }

        } catch (\Exception $e) {
            // Safe fallback
            if ($request->ajax()) {
                return response()->json(['success' => false, 'msg' => 'Security check failure'], 500);
            }
            return redirect()->back()->with(['danger' => 'System security encounter error.']);
        }

        // 6. Handle Unauthorized (Redirect to Dashboard with message)
        return $this->handleUnauthorized($request);
    }

    /**
     * Professional unauthorized handler – Mirroring perfume_admin logic
     */
    protected function handleUnauthorized(Request $request)
    {
        $message = __('admin.dosenot_hav_permission');

        if ($request->ajax()) {
            return response()->json([
                'success' => false, 
                'msg' => $message
            ], 403);
        }

        // Redirect to dashboard instead of abort(403)
        return redirect()->route('admin.dashboard.index')->with(['danger' => $message]);
    }
}
