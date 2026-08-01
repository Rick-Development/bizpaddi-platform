<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ProjectManagement\Models\Task;
use Modules\ProjectManagement\Models\Project;

use App\Models\User;
use App\Models\Admin; // Keep if needed for something else, or remove
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::with(['project', 'assignee'])
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->project_id, function ($query, $id) {
                $query->where('project_id', $id);
            })
            ->when($request->assigned_to, function ($query, $id) {
                $query->where('assigned_to', $id);
            })
            ->latest()
            ->paginate(20);

        return view('admin::tasks.index', compact('tasks'));
    }

    public function create(Request $request)
    {
        $projects = Project::all();
        $users = User::all();
        $selectedProject = $request->project_id ? Project::with('milestones')->find($request->project_id) : null;
        $milestones = $selectedProject ? $selectedProject->milestones : collect();
        
        return view('admin::tasks.create', compact('projects', 'users', 'selectedProject', 'milestones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:pm_projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:todo,in_progress,review,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'milestone_id' => 'nullable|exists:pm_milestones,id',
        ]);

        Task::create($validated);

        return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $task->load(['project', 'assignee', 'comments.user', 'media']);
        return view('admin::tasks.show', compact('task'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        $task->update(['status' => $validated['status']]);

        return back()->with('success', 'Task status updated to ' . ucfirst($validated['status'])); 
    }

    public function updateApproval(Request $request, Task $task)
    {
        $task->update([
            'requires_client_approval' => $request->has('requires_client_approval')
        ]);

        return back()->with('success', 'Approval requirement updated.');
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        $users = User::all();
        $task->load('project.milestones');
        $milestones = $task->project ? $task->project->milestones : collect();
        return view('admin::tasks.edit', compact('task', 'projects', 'users', 'milestones'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:pm_projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:todo,in_progress,review,done',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'milestone_id' => 'nullable|exists:pm_milestones,id',
        ]);

        $task->update($validated);

        return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return back()->with('success', 'Task deleted successfully.');
    }
}
