<?php

namespace Modules\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    /**
     * GET /api/v1/admin/profile
     * Get the authenticated admin's profile.
     */
    public function profile(Request $request)
    {
        $admin = $request->user();

        return $this->sendResponse([
            'id'     => $admin->id,
            'name'   => $admin->name,
            'email'  => $admin->email,
            'role'   => $admin->role, // assuming role or roles relation exists
            'phone'  => $admin->phone,
            'avatar' => $admin->getFirstMediaUrl('avatar'),
        ], 'Admin profile retrieved successfully.');
    }

    /**
     * POST /api/v1/admin/profile
     * Update the authenticated admin's profile.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $admin = $request->user();
        $admin->update($request->only(['name', 'phone']));

        return $this->sendResponse($admin->only(['id', 'name', 'email', 'phone']), 'Profile updated successfully.');
    }

    /**
     * POST /api/v1/admin/change-password
     * Change password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        $admin = $request->user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return $this->sendError('Current password is incorrect.', 400);
        }

        $admin->update(['password' => Hash::make($request->new_password)]);

        return $this->sendResponse([], 'Password changed successfully.');
    }

    /**
     * POST /api/v1/admin/logout
     * Logout and revoke token.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->sendResponse([], 'Logged out successfully.');
    }

    /**
     * POST /api/v1/admin/profile/picture
     * Update the authenticated user's profile picture.
     */
    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $admin = $request->user();

        // Clear old avatars
        $admin->clearMediaCollection('avatar');

        // Add new avatar
        $admin->addMediaFromRequest('avatar')->toMediaCollection('avatar');

        return $this->sendResponse([
            'avatar' => $admin->getFirstMediaUrl('avatar')
        ], 'Profile picture updated successfully.');
    }

    /**
     * DELETE /api/v1/admin/profile
     * Soft delete the authenticated user's account.
     */
    public function deleteAccount(Request $request)
    {
        $admin = $request->user();

        // Revoke all tokens
        $admin->tokens()->delete();

        // Soft delete the user
        $admin->delete();

        return $this->sendResponse([], 'Account deleted successfully.');
    }
}
