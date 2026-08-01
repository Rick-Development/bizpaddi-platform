<?php

namespace Modules\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends ApiController
{
    /**
     * GET /api/v1/admin/roles
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return $this->sendResponse($roles, 'Roles retrieved successfully.');
    }

    /**
     * POST /api/v1/admin/roles
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles',
            'guard_name' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create([
            'name' => $request->name, 
            'guard_name' => $request->guard_name ?? 'admin'
        ]);

        if ($request->has('permissions')) {
            $permissions = collect($request->permissions)->map(function ($perm) use ($role) {
                return Permission::findOrCreate($perm, $role->guard_name);
            });
            $role->syncPermissions($permissions);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return $this->sendResponse($role->load('permissions'), 'Role created successfully.', 201);
    }

    /**
     * PUT /api/v1/admin/roles/{id}
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) return $this->sendError('Role not found.', 404);

        $request->validate([
            'name' => 'sometimes|string|unique:roles,name,' . $id,
            'guard_name' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        if ($request->has('name')) {
            $role->name = $request->name;
        }

        if ($request->has('guard_name')) {
            $role->guard_name = $request->guard_name;
        }

        $role->save();

        if ($request->has('permissions')) {
            $permissions = collect($request->permissions)->map(function ($perm) use ($role) {
                return Permission::findOrCreate($perm, $role->guard_name);
            });
            $role->syncPermissions($permissions);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return $this->sendResponse($role->load('permissions'), 'Role updated successfully.');
    }

    /**
     * DELETE /api/v1/admin/roles/{id}
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        if (!$role) return $this->sendError('Role not found.', 404);
        
        $role->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return $this->sendResponse([], 'Role deleted successfully.');
    }
}
