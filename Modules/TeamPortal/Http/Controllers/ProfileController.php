<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('teamportal::profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection('avatar');
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        unset($validated['current_password']);
        unset($validated['password_confirmation']);
        unset($validated['avatar']);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
