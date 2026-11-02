<?php

namespace Modules\SuperAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class PlatformAdminAuthController extends Controller
{
    /**
     * Show Platform Admin dedicated login form.
     */
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            if ($user->role === 'super_admin') {
                return redirect()->route('super-admin.dashboard');
            }
            if (is_null($user->tenant_id)) {
                return redirect()->to('/admin');
            }
            return redirect()->route('admin.dashboard');
        }

        return view('superadmin::auth.platform-login');
    }

    /**
     * Handle Platform Admin authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $throttleKey = 'platform-admin-login:' . strtolower($credentials['email']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->onlyInput('email');
        }

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $user = Auth::guard('admin')->user();

            // Strict Validation: Platform staff only (tenant_id must be null)
            if (!is_null($user->tenant_id) || !$user->is_active) {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                RateLimiter::hit($throttleKey, 60);

                return back()->withErrors([
                    'email' => 'Invalid credentials.',
                ])->onlyInput('email');
            }

            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            if ($user->role === 'super_admin') {
                return redirect()->intended(route('super-admin.dashboard'));
            }

            return redirect()->intended(url('/admin'));
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    /**
     * Log out Platform Admin.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('platform-admin.login');
    }
}
