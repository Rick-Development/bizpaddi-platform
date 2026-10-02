<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of administrators.
     */
    public function index(Request $request)
    {
        $query = Admin::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $admins = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total'       => Admin::count(),
            'super_admin' => Admin::where('role', 'super_admin')->count(),
            'active'      => Admin::where('is_active', true)->count(),
            'inactive'    => Admin::where('is_active', false)->count(),
        ];

        return view('admin::administrators.index', compact('admins', 'stats'));
    }

    /**
     * Show the form for creating a new administrator.
     */
    public function create()
    {
        return view('admin::administrators.create');
    }

    /**
     * Store a newly created administrator in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'password'  => ['required', Password::defaults(), 'confirmed'],
            'role'      => ['required', 'in:admin,super_admin'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $admin = Admin::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => $validated['role'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.administrators.index')
            ->with('success', "Administrator {$admin->name} created successfully.");
    }

    /**
     * Show the form for editing the specified administrator.
     */
    public function edit(Admin $administrator)
    {
        return view('admin::administrators.edit', compact('administrator'));
    }

    /**
     * Update the specified administrator in storage.
     */
    public function update(Request $request, Admin $administrator)
    {
        $currentAdmin = $request->user('admin');

        $rules = [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins')->ignore($administrator->id),
            ],
            'role'      => ['required', 'in:admin,super_admin'],
            'is_active' => ['nullable', 'boolean'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', Password::defaults(), 'confirmed'];
        }

        $validated = $request->validate($rules);

        $isActive = $request->boolean('is_active');

        // Safeguard: Cannot deactivate yourself
        if ($administrator->id === $currentAdmin->id && !$isActive) {
            return back()->withErrors([
                'is_active' => 'You cannot deactivate your own administrator account.',
            ])->withInput();
        }

        // Safeguard: Cannot demote or deactivate the last super admin
        if ($administrator->isSuperAdmin()) {
            $otherSuperAdminsCount = Admin::where('role', 'super_admin')
                ->where('id', '!=', $administrator->id)
                ->where('is_active', true)
                ->count();

            if ($otherSuperAdminsCount === 0) {
                if ($validated['role'] !== 'super_admin') {
                    return back()->withErrors([
                        'role' => 'Cannot demote the last active Super Admin. Assign another Super Admin first.',
                    ])->withInput();
                }

                if (!$isActive) {
                    return back()->withErrors([
                        'is_active' => 'Cannot deactivate the last active Super Admin.',
                    ])->withInput();
                }
            }
        }

        $administrator->name      = $validated['name'];
        $administrator->email     = $validated['email'];
        $administrator->role      = $validated['role'];
        $administrator->is_active = $isActive;

        if ($request->filled('password')) {
            $administrator->password = Hash::make($validated['password']);
        }

        $administrator->save();

        return redirect()
            ->route('admin.administrators.index')
            ->with('success', "Administrator {$administrator->name}'s details updated successfully.");
    }

    /**
     * Quick toggle active status for an administrator.
     */
    public function updateStatus(Request $request, Admin $administrator)
    {
        $currentAdmin = $request->user('admin');

        if ($administrator->id === $currentAdmin->id) {
            return back()->with('error', 'You cannot change the status of your own account.');
        }

        // Cannot deactivate last super admin
        if ($administrator->isSuperAdmin() && $administrator->is_active) {
            $otherSuperAdmins = Admin::where('role', 'super_admin')
                ->where('id', '!=', $administrator->id)
                ->where('is_active', true)
                ->count();

            if ($otherSuperAdmins === 0) {
                return back()->with('error', 'Cannot deactivate the last active Super Admin.');
            }
        }

        $administrator->is_active = !$administrator->is_active;
        $administrator->save();

        $statusStr = $administrator->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Administrator {$administrator->name} {$statusStr} successfully.");
    }

    /**
     * Remove the specified administrator from storage.
     */
    public function destroy(Request $request, Admin $administrator)
    {
        $currentAdmin = $request->user('admin');

        // Safeguard: Cannot delete own account
        if ($administrator->id === $currentAdmin->id) {
            return back()->with('error', 'You cannot delete your own administrator account.');
        }

        // Safeguard: Cannot delete the last super admin
        if ($administrator->isSuperAdmin()) {
            $otherSuperAdmins = Admin::where('role', 'super_admin')
                ->where('id', '!=', $administrator->id)
                ->count();

            if ($otherSuperAdmins === 0) {
                return back()->with('error', 'Cannot delete the last Super Admin in the system.');
            }
        }

        $name = $administrator->name;
        $administrator->delete();

        return redirect()
            ->route('admin.administrators.index')
            ->with('success', "Administrator {$name} deleted successfully.");
    }
}
