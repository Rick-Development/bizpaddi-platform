<?php

namespace Modules\TeamPortal\Http\Controllers\PM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Project;
use Modules\ProjectManagement\Models\Task;
use Modules\ProjectManagement\Models\Milestone;

class TaskController extends Controller
{
    public function create(Project $project)
    {
        $project->load('teamMembers', 'milestones');
        $users = \App\Models\User::where('status', 'active')->orderBy('name')->get();
        
        return view('teamportal::pm.tasks.create', compact('project', 'users'));
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'required|in:low,medium,high',
            'status' => 'nullable|in:todo,in_progress,review,done',
            'due_date' => 'nullable|date',
            'milestone_id' => 'nullable|exists:pm_milestones,id',
        ]);

        // Automatically add the user to the team if they aren't already part of it
        if (!$project->teamMembers->contains($validated['assigned_to'])) {
            $project->teamMembers()->syncWithoutDetaching([$validated['assigned_to'] => [
                'role' => 'member',
                'joined_at' => now()
            ]]);
        }

        $task = $project->tasks()->create(array_merge($validated, [
            'assigned_by' => auth()->id(),
        ]));

        // TODO: Send notification to assigned user

        return redirect()->route('team.pm.projects.show', $project)
            ->with('success', 'Task created and assigned successfully!');
    }

    public function edit(Project $project, Task $task)
    {
        $project->load('teamMembers', 'milestones');
        $users = \App\Models\User::where('status', 'active')->orderBy('name')->get();
        
        return view('teamportal::pm.tasks.edit', compact('project', 'task', 'users'));
    }

    public function update(Request $request, Project $project, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:todo,in_progress,review,done',
            'due_date' => 'nullable|date',
            'milestone_id' => 'nullable|exists:pm_milestones,id',
        ]);

        // Automatically add the user to the team if they aren't already part of it
        if (!$project->teamMembers->contains($validated['assigned_to'])) {
            $project->teamMembers()->syncWithoutDetaching([$validated['assigned_to'] => [
                'role' => 'member',
                'joined_at' => now()
            ]]);
        }

        $task->update($validated);

        return redirect()->route('team.pm.projects.show', $project)
            ->with('success', 'Task updated successfully!');
    }

    public function destroy(Project $project, Task $task)
    {
        $task->delete();

        return redirect()->back()->with('success', 'Task deleted successfully!');
    }

    public function bulkAssign(Request $request, Project $project)
    {
        $validated = $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'exists:pm_tasks,id',
            'assigned_to' => 'required|exists:users,id',
        ]);

        // Imported data may have task assignees without a matching pivot row yet.
        if (!$project->teamMembers->contains($validated['assigned_to'])) {
            $project->teamMembers()->syncWithoutDetaching([$validated['assigned_to'] => [
                'role' => 'member',
                'joined_at' => now()
            ]]);
        }

        Task::whereIn('id', $validated['task_ids'])
            ->where('project_id', $project->id)
            ->update([
                'assigned_to' => $validated['assigned_to'],
                'assigned_by' => auth()->id(),
            ]);

        return redirect()->back()->with('success', count($validated['task_ids']) . ' tasks reassigned successfully!');
    }
}
