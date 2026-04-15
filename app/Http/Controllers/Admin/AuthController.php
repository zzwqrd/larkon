<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display the admin login view.
     */
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an incoming authentication request for admins.
     */
    public function login(AdminLoginRequest $request)
    {
        $request->authenticate();

        $admin = Auth::guard('admin')->user();

        // Check if admin is blocked
        if ($admin->is_blocked) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->back()->withErrors([
                'email' => __('messages.account_blocked_contact_admin'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('root'));
    }

    /**
     * Destroy an authenticated admin session.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.show.login');
    }

    /**
     * Change language for admin session.
     */
    public function changeLnag(Request $request, $lang)
    {
        if (in_array($lang, ['ar', 'en'])) {
            session()->put('locale', $lang);
            
            // If logged in, update admin's preferred locale
            if (Auth::guard('admin')->check()) {
                $admin = Auth::guard('admin')->user();
                $admin->update(['locale' => $lang]);
            }
        }
        return redirect()->back();
    }
}
