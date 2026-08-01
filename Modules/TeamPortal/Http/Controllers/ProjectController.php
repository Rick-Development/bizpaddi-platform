<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $projects = Project::where(function($query) use ($user) {
            $query->whereHas('tasks', function($q) use ($user) {
                $q->where('assigned_to', $user->id);
            })->orWhere('manager_id', $user->id);
        })
        ->with('client')
        ->latest()
        ->paginate(12);

        return view('teamportal::projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $user = auth()->user();
        
        // Load relationships
        $project->load(['client', 'milestones']);
        
        // Fetch tasks for this project
        // If user is PM, show all project tasks, otherwise only their own
        $query = $project->tasks()->with(['assignee']);
        
        if ($project->manager_id !== $user->id) {
            $query->where('assigned_to', $user->id);
        }
        
        $tasks = $query->latest()->get();

        return view('teamportal::projects.show', compact('project', 'tasks'));
    }
}
