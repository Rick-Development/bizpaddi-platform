<?php

namespace Modules\AiEmployees\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\AiEmployees\Models\AiEmployee;

class AiEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Only show root-level primary managers, never sub-intelligences in the main list
        $query = AiEmployee::whereNull('parent_id')->where('is_sub_agent', false)->withCount('subAgents');
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $employees = $query->get();
        $currentRole = $request->role;

        return view('aiemployees::admin.index', compact('employees', 'currentRole'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('aiemployees::admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'description' => 'nullable|string',
            'system_prompt' => 'required|string',
            'ai_model_id' => 'nullable|integer',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|integer',
            'is_sub_agent' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_sub_agent'] = $request->has('is_sub_agent');

        $employee = AiEmployee::create($validated);

        $redirectUrl = ($employee->is_sub_agent && $employee->parent_id) 
            ? route('admin.aiemployees.employees.edit', $employee->parent_id)
            : route('admin.aiemployees.employees.index', ['role' => $employee->role]);

        if ($request->ajax() || $request->wantsJson()) {
            session()->flash('success', 'AI Employee created successfully.');
            return response()->json([
                'success' => true,
                'redirect' => $redirectUrl
            ]);
        }

        return redirect()->to($redirectUrl)->with('success', 'AI Employee created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $employee = AiEmployee::findOrFail($id);
        return view('aiemployees::admin.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employee = AiEmployee::with(['subAgents', 'parent'])->findOrFail($id);
        return view('aiemployees::admin.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'description' => 'nullable|string',
            'system_prompt' => 'required|string',
            'ai_model_id' => 'nullable|integer',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|integer',
            'is_sub_agent' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_sub_agent'] = $request->has('is_sub_agent');

        $employee = AiEmployee::findOrFail($id);
        $employee->update($validated);

        $redirectUrl = ($employee->is_sub_agent && $employee->parent_id) 
            ? route('admin.aiemployees.employees.edit', $employee->parent_id)
            : route('admin.aiemployees.employees.index', ['role' => $employee->role]);

        if ($request->ajax() || $request->wantsJson()) {
            session()->flash('success', 'AI Employee updated successfully.');
            return response()->json([
                'success' => true,
                'redirect' => $redirectUrl
            ]);
        }

        return redirect()->to($redirectUrl)->with('success', 'AI Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $employee = AiEmployee::findOrFail($id);
        $role = $employee->role;
        $employee->delete();

        if ($request->ajax() || $request->wantsJson()) {
            session()->flash('success', 'AI Employee deleted successfully.');
            return response()->json([
                'success' => true,
                'redirect' => route('admin.aiemployees.employees.index', ['role' => $role])
            ]);
        }

        return redirect()->route('admin.aiemployees.employees.index', ['role' => $role])->with('success', 'AI Employee deleted successfully.');
    }

    /**
     * Toggle the active status of the specified employee.
     */
    public function toggleStatus(Request $request, $id)
    {
        $employee = AiEmployee::findOrFail($id);
        $employee->is_active = !$employee->is_active;
        $employee->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $employee->is_active,
                'message' => 'Status updated successfully.'
            ]);
        }

        return redirect()->back()->with('success', 'AI Employee status updated.');
    }
}
