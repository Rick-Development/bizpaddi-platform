<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        return view('admin::roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function($item) {
            return explode('.', $item->name)[0]; // Group by module (e.g., 'invoices', 'quotes')
        });
        return view('admin::roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function($item) {
            return explode('.', $item->name)[0];
        });
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        return view('admin::roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]); // Revoke all if none sent
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        // Prevent deleting super_admin or critical roles logic if needed
        $role->delete();
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
