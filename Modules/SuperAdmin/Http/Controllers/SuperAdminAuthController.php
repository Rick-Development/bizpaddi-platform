<?php

namespace Modules\SuperAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Primary attempt
        $authenticated = Auth::guard('admin')->attempt(['email' => $email, 'password' => $password], $remember);

        // Fallback attempt with trimmed password in case of accidental whitespace
        if (!$authenticated && trim($password) !== $password) {
            $authenticated = Auth::guard('admin')->attempt(['email' => $email, 'password' => trim($password)], $remember);
        }

        if ($authenticated) {
            $user = Auth::guard('admin')->user();

            // Strict Role Validation: Super Admin only
            if ($user->role !== 'super_admin') {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Access denied. Only Super Administrators can log in through this portal.',
                ])->onlyInput('email');
            }

            if (!$user->is_active) {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'email' => 'Your administrator account has been deactivated.',
                ]);
            }

            $request->session()->regenerate();
            return redirect()->intended(route('super-admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Invalid credentials for Super Admin portal. Ensure email is admin@bizpaddi.com and master password is correct.',
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
