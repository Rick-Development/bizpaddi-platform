<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    /**
     * GET /api/v1/team/profile
     * Get the authenticated team member's profile.
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        return $this->sendResponse([
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'role'       => $user->role,
            'department' => $user->department,
            'phone'      => $user->phone,
            'status'     => $user->status,
            'avatar'     => $user->getFirstMediaUrl('avatar'),
        ], 'Profile retrieved successfully.');
    }

    /**
     * POST /api/v1/team/profile
     * Update the authenticated team member's profile.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = $request->user();
        $user->update($request->only(['name', 'phone']));

        return $this->sendResponse($user->only(['id', 'name', 'email', 'phone']), 'Profile updated successfully.');
    }

    /**
     * POST /api/v1/team/change-password
     * Change the authenticated team member's password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->sendError('Current password is incorrect.', 400);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return $this->sendResponse([], 'Password changed successfully.');
    }

    /**
     * POST /api/v1/team/logout
     * Revoke the current API token.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->sendResponse([], 'Logged out successfully.');
    }

    /**
     * POST /api/v1/team/profile/picture
     * Update the authenticated user's profile picture.
     */
    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $user = $request->user();

        // Clear old avatars
        $user->clearMediaCollection('avatar');

        // Add new avatar
        $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');

        return $this->sendResponse([
            'avatar' => $user->getFirstMediaUrl('avatar')
        ], 'Profile picture updated successfully.');
    }

    /**
     * DELETE /api/v1/team/profile
     * Soft delete the authenticated user's account.
     */
    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        // Revoke all tokens
        $user->tokens()->delete();

        // Soft delete the user
        $user->delete();

        return $this->sendResponse([], 'Account deleted successfully.');
    }
}
