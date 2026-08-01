<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Models\Admin;
use Modules\Clients\Models\Client;

class UnifiedAuthController extends Controller
{
    /**
     * Unified Login — auto-detects user type (Admin → Team → Client)
     * POST /api/v1/auth/login
     *
     * Body: { "email": "", "password": "" }
     * No "guard" needed — backend resolves the user type automatically.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $email    = $request->email;
        $password = $request->password;

        // 1. Check Admin
        $admin = Admin::where('email', $email)->first();
        if ($admin && Hash::check($password, $admin->password)) {
            if (!$admin->is_active) {
                return $this->errorResponse('Admin account is inactive.', 403);
            }
            return $this->loginResponse($admin, $admin->createToken('admin-app')->plainTextToken, 'admin');
        }

        // 2. Check Team Member
        $user = User::where('email', $email)->first();
        if ($user && Hash::check($password, $user->password)) {
            if (isset($user->status) && $user->status !== 'active') {
                return $this->errorResponse('Team member account is inactive.', 403);
            }
            return $this->loginResponse($user, $user->createToken('team-app')->plainTextToken, 'team');
        }

        // 3. Check Client
        $client = Client::where('email', $email)->first();
        if ($client && Hash::check($password, $client->password)) {
            if (!$client->is_active) {
                return $this->errorResponse('Client account is inactive. Please contact support.', 403);
            }
            return $this->loginResponse($client, $client->createToken('client-app')->plainTextToken, 'client');
        }

        return $this->errorResponse('Invalid credentials.', 401);
    }

    /**
     * Forgot Password
     * POST /api/v1/auth/forgot-password
     *
     * Body: { "email": "", "guard": "client|team|admin" }
     * Guard still needed here so the reset link points to the right broker.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'guard' => 'nullable|in:client,team,admin',
        ]);

        $broker = $this->getBroker($request->guard ?? 'client');

        $status = Password::broker($broker)->sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['success' => true, 'message' => 'Password reset link sent to your email.']);
        }

        return response()->json(['success' => false, 'message' => 'Unable to send reset link.'], 400);
    }

    /**
     * Reset Password
     * POST /api/v1/auth/reset-password
     *
     * Body: { "token": "", "email": "", "password": "", "password_confirmation": "", "guard": "client|team|admin" }
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|confirmed|min:8',
            'guard'                 => 'nullable|in:client,team,admin',
        ]);

        $broker = $this->getBroker($request->guard ?? 'client');

        $status = Password::broker($broker)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['success' => true, 'message' => 'Password reset successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid or expired reset token.'], 400);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function getBroker(?string $guard): string
    {
        return match ($guard) {
            'admin' => 'admins',
            'team'  => 'users',
            default => 'clients',
        };
    }

    private function loginResponse($user, string $token, string $type)
    {
        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data'    => [
                'token' => $token,
                'user'  => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'type'  => $type,  // ← mobile app uses this to know which portal to open
                ],
            ],
        ], 200);
    }

    private function errorResponse(string $message, int $code = 400)
    {
        return response()->json(['success' => false, 'message' => $message], $code);
    }
}
