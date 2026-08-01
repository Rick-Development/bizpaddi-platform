<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Project;

class ProjectController extends ApiController
{
    /**
     * GET /api/v1/team/projects
     * List all projects assigned to the authenticated team member.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $projects = Project::whereHas('tasks', function ($query) use ($user) {
            $query->where('assigned_to', $user->id);
        })
        ->orWhere('manager_id', $user->id)
        ->latest()
        ->paginate(15);

        return $this->sendResponse($projects, 'Projects retrieved successfully.');
    }

    /**
     * GET /api/v1/team/projects/{id}
     * Get details of a specific project, including tasks assigned to the user.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        $project = Project::with([
            'client:id,name,company_name',
            'manager:id,name',
            'milestones' => function ($query) {
                $query->orderBy('due_date');
            },
            'tasks' => function ($query) use ($user) {
                // Only load tasks assigned to this user, unless they are the project manager
                if (!$user->isProjectManager()) {
                    $query->where('assigned_to', $user->id);
                }
                $query->with('assignee:id,name');
            }
        ])->find($id);

        if (!$project) {
            return $this->sendError('Project not found.', 404);
        }

        // Verify access: User must be manager or have tasks assigned in this project
        $hasAccess = $project->manager_id === $user->id || $project->tasks->where('assigned_to', $user->id)->count() > 0;

        if (!$hasAccess) {
             return $this->sendError('You do not have access to this project.', 403);
        }

        return $this->sendResponse($project, 'Project details retrieved successfully.');
    }
}
