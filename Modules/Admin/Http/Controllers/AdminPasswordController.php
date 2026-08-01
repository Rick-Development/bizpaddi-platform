<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AdminPasswordController extends Controller
{
    public function edit(Request $request)
    {
        return view('admin::account.password', [
            'admin' => $request->user('admin'),
        ]);
    }

    public function update(Request $request)
    {
        $admin = $request->user('admin');

        $validated = $request->validate([
            'current_password' => ['required', 'current_password:admin'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $admin->forceFill([
            'password' => Hash::make($validated['password']),
            'remember_token' => Str::random(60),
        ])->save();

        $request->session()->regenerate();

        return redirect()
            ->route('admin.account.password.edit')
            ->with('success', 'Your admin password has been updated successfully.');
    }
}
