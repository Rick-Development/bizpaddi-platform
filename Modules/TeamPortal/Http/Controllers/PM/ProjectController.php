<?php

namespace Modules\TeamPortal\Http\Controllers\PM;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Project;
use Modules\ProjectManagement\Models\Task;

class ProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $projects = Project::with(['client', 'tasks', 'teamMembers'])
            ->manageableBy($user)
            ->latest()
            ->paginate(12);

        return view('teamportal::pm.projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        // Load relationships
        $project->load(['client', 'tasks.assignee', 'tasks.milestone', 'teamMembers', 'milestones']);
        
        // Get tasks grouped by status
        $tasksByStatus = [
            'todo' => $project->tasks->where('status', 'todo'),
            'in_progress' => $project->tasks->where('status', 'in_progress'),
            'review' => $project->tasks->where('status', 'review'),
            'done' => $project->tasks->where('status', 'done'),
        ];

        // Team performance metrics
        $teamPerformance = $project->teamMembers->map(function($member) use ($project) {
            $assignedTasks = $project->tasks->where('assigned_to', $member->id);
            $completedTasks = $assignedTasks->where('status', 'done');
            
            return [
                'member' => $member,
                'assigned' => $assignedTasks->count(),
                'completed' => $completedTasks->count(),
                'in_progress' => $assignedTasks->whereIn('status', ['todo', 'in_progress'])->count(),
                'completion_rate' => $assignedTasks->count() > 0 
                    ? round(($completedTasks->count() / $assignedTasks->count()) * 100) 
                    : 0,
            ];
        });

        return view('teamportal::pm.projects.show', compact('project', 'tasksByStatus', 'teamPerformance'));
    }

    public function team(Project $project)
    {
        $project->load('teamMembers');
        
        // Get all users who could be added to the project
        $availableUsers = User::where('status', 'active')
            ->whereDoesntHave('managedProjects', function($query) use ($project) {
                $query->where('id', $project->id);
            })
            ->whereNotIn('id', $project->teamMembers->pluck('id'))
            ->get();

        return view('teamportal::pm.projects.team', compact('project', 'availableUsers'));
    }

    public function addTeamMember(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:member,lead',
        ]);

        $project->teamMembers()->syncWithoutDetaching([$request->user_id => [
            'role' => $request->role,
            'joined_at' => now(),
        ]]);

        return redirect()->back()->with('success', 'Team member added successfully!');
    }

    public function removeTeamMember(Project $project, User $user)
    {
        $project->teamMembers()->detach($user->id);

        return redirect()->back()->with('success', 'Team member removed successfully!');
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,active,on_hold,completed,cancelled',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        $project->update($validated);

        return redirect()->back()->with('success', 'Project updated successfully!');
    }
}
