<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Milestone;
use Modules\ProjectManagement\Models\Project;

class PMMilestoneController extends Controller
{
    public function index(Request $request)
    {
        $milestones = Milestone::with('project')
            ->when($request->project_id, function($q) use ($request) {
                return $q->where('project_id', $request->project_id);
            })
            ->latest()
            ->paginate(20);

        $projects = Project::all();
        
        return view('admin::pm-milestones.index', compact('milestones', 'projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:pm_projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        Milestone::create($request->all());

        return back()->with('success', 'Milestone created successfully.');
    }

    public function edit(Milestone $pm_milestone)
    {
        $projects = Project::all();
        return view('admin::pm-milestones.edit', compact('pm_milestone', 'projects'));
    }

    public function update(Request $request, Milestone $pm_milestone)
    {
        $request->validate([
            'project_id' => 'required|exists:pm_projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,completed',
            'order' => 'nullable|integer',
        ]);

        $pm_milestone->update($request->all());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Milestone updated successfully.']);
        }

        return redirect()->route('admin.pm-milestones.index')->with('success', 'Milestone updated successfully.');
    }

    public function destroy(Milestone $pm_milestone)
    {
        $pm_milestone->delete();

        return back()->with('success', 'Milestone deleted successfully.');
    }
}
