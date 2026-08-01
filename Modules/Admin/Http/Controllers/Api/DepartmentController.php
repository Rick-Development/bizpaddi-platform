<?php

namespace Modules\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Admin\Models\Department;

class DepartmentController extends ApiController
{
    /**
     * GET /api/v1/admin/departments
     */
    public function index()
    {
        $departments = Department::all();
        return $this->sendResponse($departments, 'Departments retrieved successfully.');
    }

    /**
     * POST /api/v1/admin/departments
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:departments,name', // NOTE: changed to departments assuming correct table
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
        
        $validated['is_active'] = $request->boolean('is_active', true);

        $department = Department::create($validated);
        return $this->sendResponse($department, 'Department created successfully.', 201);
    }

    /**
     * PUT /api/v1/admin/departments/{id}
     */
    public function update(Request $request, $id)
    {
        $department = Department::find($id);
        if (!$department) return $this->sendError('Department not found.', 404);

        $validated = $request->validate([
            'name' => 'sometimes|string|unique:departments,name,' . $id,
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $department->update($validated);
        return $this->sendResponse($department->fresh(), 'Department updated successfully.');
    }

    /**
     * DELETE /api/v1/admin/departments/{id}
     */
    public function destroy($id)
    {
        $department = Department::find($id);
        if (!$department) return $this->sendError('Department not found.', 404);
        
        $department->delete();
        return $this->sendResponse([], 'Department deleted successfully.');
    }
}
