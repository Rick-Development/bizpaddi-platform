<?php

namespace Modules\ClientPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $client = auth()->guard('client')->user();
        return view('clientportal::profile.edit', compact('client'));
    }

    public function update(Request $request)
    {
        $client = auth()->guard('client')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('clients')->ignore($client->id)],
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $client->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            $client->clearMediaCollection('avatar');
            $client->addMediaFromRequest('avatar')->toMediaCollection('avatar');
        }

        unset($validated['current_password']);
        unset($validated['password_confirmation']);
        unset($validated['avatar']);

        $client->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
