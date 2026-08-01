<?php

namespace Modules\TeamPortal\Http\Controllers\Api\PM;

use Illuminate\Http\Request;
use Modules\TeamPortal\Http\Controllers\Api\ApiController;
use Modules\TeamPortal\Models\WorkLog;
use Modules\ProjectManagement\Models\Project;
use App\Models\User;

class WorkLogController extends ApiController
{
    /**
     * GET /api/v1/team/pm/work-logs
     * List work logs for team members in projects managed by the PM.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('Unauthorized access.', 403);
        }

        // Get IDs of projects managed by the PM
        $managedProjectIds = Project::where('manager_id', $user->id)->pluck('id');

        // Find users who have tasks assigned to them in those projects
        $userIdsWithTasks = \Modules\ProjectManagement\Models\Task::whereIn('project_id', $managedProjectIds)
            ->pluck('assigned_to')
            ->filter()
            ->unique()
            ->toArray();

        // Alternatively or additionally, find users who are in the teamMembers relationship
        $userIdsInTeams = \Illuminate\Support\Facades\DB::table('project_team_members')
            ->whereIn('project_id', $managedProjectIds)
            ->pluck('user_id')
            ->toArray();

        // Merge and unique
        $teamUserIds = array_unique(array_merge($userIdsWithTasks, $userIdsInTeams));

        // Get work logs for these users
        $query = WorkLog::whereIn('user_id', $teamUserIds)->with('user:id,name,email');

        // Optional filtering by specific user
        if ($request->has('user_id')) {
            if (in_array($request->user_id, $teamUserIds)) {
                $query->where('user_id', $request->user_id);
            } else {
                return $this->sendError('You cannot view work logs for this user.', 403);
            }
        }

        // Optional filtering by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('start_time', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $workLogs = $query->latest('start_time')->paginate(20);

        return $this->sendResponse($workLogs, 'Team work logs retrieved successfully.');
    }
}
