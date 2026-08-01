<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\Department;

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
        $request->validate([
            'name' => 'required|string|unique:departments,name|max:255',
            'description' => 'nullable|string',
        ]);

        Department::create($request->all());

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        return view('admin::departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
        ]);

        $department->update($request->all());

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
