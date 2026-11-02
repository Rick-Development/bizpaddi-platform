<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\Department;

use Illuminate\Validation\Rule;
use App\Scopes\TenantScope;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::latest()->paginate(10);
        return view('admin::departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin::departments.create');
    }

    public function store(Request $request)
    {
        $tenantId = TenantScope::getActiveTenantId();

        $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('departments', 'name')->where('tenant_id', $tenantId),
            ],
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'description', 'is_active']);
        $data['tenant_id'] = $tenantId;

        Department::create($data);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        return view('admin::departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $tenantId = TenantScope::getActiveTenantId();

        $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('departments', 'name')->where('tenant_id', $tenantId)->ignore($department->id),
            ],
            'description' => 'nullable|string',
        ]);

        $department->update($request->only(['name', 'description', 'is_active']));

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('admin.departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
