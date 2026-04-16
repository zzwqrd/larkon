<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LanguageSwitcher
{
    /**
     * Handle locale switching for admin and site.
     * Ported from perfume_admin.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!empty(Session::get('lang'))) {
            App::setLocale(Session::get('lang'));
        } else {
            // Default to 'ar' or use config
            App::setLocale(config('app.locale', 'ar'));
        }

        try {
            if (Auth::guard('admin')->check()) {
                $user = Auth::guard('admin')->user();
                if ($user->locale !== App::getLocale()) {
                    $user->update(['locale' => App::getLocale()]);
                }
            }
        } catch (\Exception $e) {
            // Fail silently if DB connection is lost or column missing
        }

        return $next($request);
    }
}
