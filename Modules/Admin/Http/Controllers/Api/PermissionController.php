<?php

namespace Modules\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends ApiController
{
    /**
     * GET /api/v1/admin/permissions
     */
    public function index()
    {
        $permissions = Permission::all();
        return $this->sendResponse($permissions, 'Permissions retrieved successfully.');
    }

    /**
     * POST /api/v1/admin/permissions
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'guard_name' => 'nullable|string'
        ]);

        $permission = Permission::create([
            'name' => $request->name, 
            'guard_name' => $request->guard_name ?? 'admin'
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return $this->sendResponse($permission, 'Permission created successfully.', 201);
    }

    /**
     * PUT /api/v1/admin/permissions/{id}
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return $this->sendError('Permission not found.', 404);
        }

        $request->validate([
            'name' => 'sometimes|string|unique:permissions,name,' . $id,
            'guard_name' => 'nullable|string'
        ]);

        if ($request->has('name')) {
            $permission->name = $request->name;
        }
        
        if ($request->has('guard_name')) {
            $permission->guard_name = $request->guard_name;
        }

        $permission->save();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return $this->sendResponse($permission, 'Permission updated successfully.');
    }

    /**
     * DELETE /api/v1/admin/permissions/{id}
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return $this->sendError('Permission not found.', 404);
        }
        
        $permission->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return $this->sendResponse([], 'Permission deleted successfully.');
    }
}
