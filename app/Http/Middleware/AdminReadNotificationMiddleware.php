<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class AdminReadNotificationMiddleware
{
    /**
     * Automatically mark notifications as read when the ID is passed in the URL.
     * Ported from perfume_admin.
     */
    public function handle(Request $request, Closure $next)
    {
        if (isset(request()->notification) && auth('admin')->check()) {
            $notification = auth('admin')->user()->notifications()
                                 ->where('read_at', null)
                                 ->find(request()->notification);
            
            if ($notification) {
                $notification->update([
                    'read_at' => Carbon::now(),
                ]);
            }
        }
        return $next($request);
    }
}
