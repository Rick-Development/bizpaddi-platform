<?php

namespace Modules\SuperAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class SuperAdminAuthController extends Controller
{
    /**
     * Show Super Admin dedicated login form.
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

        return view('superadmin::auth.login');
    }

    /**
     * Handle Super Admin authentication attempt.
     */
    public function login(Request $request)
    {
        $request->merge([
            'email' => strtolower(trim((string) $request->input('email'))),
        ]);

        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = $request->email;
        $password = (string) $request->password;
        $remember = $request->filled('remember');
        $throttleKey = 'superadmin-login:' . $email . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->onlyInput('email');
        }

        // Primary attempt
        $authenticated = Auth::guard('admin')->attempt(['email' => $email, 'password' => $password], $remember);

        // Fallback attempt with trimmed password in case of accidental whitespace
        if (!$authenticated && trim($password) !== $password) {
            $authenticated = Auth::guard('admin')->attempt(['email' => $email, 'password' => trim($password)], $remember);
        }

        if ($authenticated) {
            $user = Auth::guard('admin')->user();

            // Strict Role Validation: Super Admin only
            if ($user->role !== 'super_admin' || !$user->is_active) {
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
            return redirect()->intended(route('super-admin.dashboard'));
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    /**
     * Log out Super Admin.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('super-admin.login');
    }
}
