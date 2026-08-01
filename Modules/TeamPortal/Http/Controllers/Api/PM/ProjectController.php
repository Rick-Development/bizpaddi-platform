<?php

namespace Modules\TeamPortal\Http\Controllers\Api\PM;

use Illuminate\Http\Request;
use Modules\TeamPortal\Http\Controllers\Api\ApiController;
use Modules\ProjectManagement\Models\Project;

class ProjectController extends ApiController
{
    /**
     * GET /api/v1/team/pm/projects
     * List projects managed by this PM.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('Unauthorized access.', 403);
        }

        $projects = Project::where('manager_id', $user->id)
            ->with(['client', 'tasks'])
            ->latest()
            ->paginate(15);

        return $this->sendResponse($projects, 'Managed projects retrieved successfully.');
    }

    /**
     * GET /api/v1/team/pm/projects/{id}
     * Detail view of a managed project.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('Unauthorized access.', 403);
        }

        $project = Project::with(['client', 'tasks.assignee', 'milestones'])->where('manager_id', $user->id)->find($id);

        if (!$project) {
            return $this->sendError('Project not found.', 404);
        }

        return $this->sendResponse($project, 'Project details retrieved successfully.');
    }
}
