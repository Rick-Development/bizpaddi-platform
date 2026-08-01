<?php

namespace Modules\TeamPortal\Http\Controllers\Api\PM;

use Illuminate\Http\Request;
use Modules\TeamPortal\Http\Controllers\Api\ApiController;
use Modules\ProjectManagement\Models\Project;
use Modules\ProjectManagement\Models\Task;

class DashboardController extends ApiController
{
    /**
     * GET /api/v1/team/pm/dashboard
     * PM specific dashboard showing overall project stats.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('Unauthorized access. Only Project Managers can view this.', 403);
        }

        $managedProjects = Project::where('manager_id', $user->id)->pluck('id');

        $stats = [
            'total_projects' => $managedProjects->count(),
            'active_projects' => Project::where('manager_id', $user->id)->where('status', 'in_progress')->count(),
            'pending_tasks' => Task::whereIn('project_id', $managedProjects)->whereIn('status', ['todo', 'in_progress'])->count(),
            'completed_tasks' => Task::whereIn('project_id', $managedProjects)->where('status', 'done')->count(),
        ];

        return $this->sendResponse($stats, 'PM Dashboard data retrieved successfully.');
    }
}
