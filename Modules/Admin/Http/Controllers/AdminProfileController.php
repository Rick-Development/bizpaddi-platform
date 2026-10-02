<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    /**
     * Show the form for editing the authenticated admin's profile details.
     */
    public function edit(Request $request)
    {
        $admin = $request->user('admin');

        return view('admin::account.profile', compact('admin'));
    }

    /**
     * Update the authenticated admin's profile details.
     */
    public function update(Request $request)
    {
        $admin = $request->user('admin');

        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins')->ignore($admin->id),
            ],
        ];

        // If the admin also entered a new password on the profile page
        if ($request->filled('password')) {
            $rules['current_password'] = ['required', 'current_password:admin'];
            $rules['password'] = ['required', Password::defaults(), 'confirmed'];
        }

        $validated = $request->validate($rules);

        $admin->name  = $validated['name'];
        $admin->email = $validated['email'];

        if ($request->filled('password')) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()
            ->route('admin.account.profile.edit')
            ->with('success', 'Admin profile details updated successfully.');
    }
}
