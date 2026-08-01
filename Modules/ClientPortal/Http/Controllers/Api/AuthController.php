<?php

namespace Modules\ClientPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\Clients\Models\Client;

class AuthController extends ApiController
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $client = Client::where('email', $request->email)->first();

        if (! $client || ! Hash::check($request->password, $client->password)) {
            return $this->sendError('Invalid credentials', 401);
        }

        if (! $client->is_active) {
            return $this->sendError('Your account is inactive. Please contact support.', 403);
        }

        // Generate Sanctum Token
        $token = $client->createToken('client-mobile-app')->plainTextToken;

        // Send login notification
        try {
            $notificationService = app(\App\Services\FirebaseNotificationService::class);
            $notificationService->sendToClient(
                $client->id,
                'Login Successful',
                'You have successfully logged in to your account.',
                ['type' => 'login', 'timestamp' => now()->toIso8601String()]
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send login notification: ' . $e->getMessage());
        }

        return $this->sendResponse([
            'token' => $token,
            'user' => $client->only(['id', 'name', 'email', 'company_name']),
        ], 'Login successful.');
    }

    public function profile(Request $request)
    {
        $client = $request->user();
        $profile = array_merge($client->toArray(), [
            'avatar' => $client->getFirstMediaUrl('avatar')
        ]);
        return $this->sendResponse($profile, 'Profile retrieved successfully.');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse([], 'Logged out successfully.');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
            'password' => 'required|string|confirmed|min:8',
            'company_name' => 'nullable|string|max:255',
        ]);

        $client = Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_name' => $request->company_name,
            'is_active' => true,
        ]);

        // Generate Sanctum Token
        $token = $client->createToken('client-mobile-app')->plainTextToken;

        return $this->sendResponse([
            'token' => $token,
            'user' => $client->only(['id', 'name', 'email', 'company_name']),
        ], 'Registration successful.');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $client = Client::where('email', $request->email)->first();

        if (!$client) {
            return $this->sendError('We could not find a user with that email address.', 404);
        }

        $status = \Illuminate\Support\Facades\Password::broker('clients')->sendResetLink(
            $request->only('email')
        );

        if ($status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
            return $this->sendResponse([], 'Password reset link sent to your email.');
        }

        return $this->sendError('Unable to send reset link. Please try again.', 500);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = \Illuminate\Support\Facades\Password::broker('clients')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
                
                $user->setRememberToken(\Illuminate\Support\Str::random(60));
            }
        );

        if ($status === \Illuminate\Support\Facades\Password::PASSWORD_RESET) {
            return $this->sendResponse([], 'Password has been reset successfully.');
        }

        return $this->sendError('Failed to reset password. Please try again.', 400);
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
            'device_type' => 'nullable|string',
        ]);

        $user = $request->user();

        // Update or create based on token to prevent duplicates
        // But users might have multiple devices. 
        // We typically want to store mapping of (user_id, fcm_token)
        
        \Modules\ClientPortal\Models\ClientDeviceToken::updateOrCreate(
            ['fcm_token' => $request->fcm_token],
            [
                'client_id' => $user->id,
                'device_type' => $request->device_type ?? 'unknown'
            ]
        );

        return $this->sendResponse([], 'Token updated successfully.');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        $user->update([
            'name' => $request->name,
            'company_name' => $request->company_name,
        ]);

        return $this->sendResponse($user->only(['id', 'name', 'email', 'company_name']), 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->sendError('Current password is incorrect.', 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return $this->sendResponse([], 'Password changed successfully.');
    }

    /**
     * POST /api/v1/client/profile/picture
     */
    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $client = $request->user();
        $client->clearMediaCollection('avatar');
        $client->addMediaFromRequest('avatar')->toMediaCollection('avatar');

        return $this->sendResponse([
            'avatar' => $client->getFirstMediaUrl('avatar')
        ], 'Profile picture updated successfully.');
    }

    /**
     * DELETE /api/v1/client/profile
     */
    public function deleteAccount(Request $request)
    {
        $client = $request->user();
        $client->tokens()->delete();
        $client->delete();

        return $this->sendResponse([], 'Account deleted successfully.');
    }
}
