<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\TeamPortal\Models\WorkLog;

class WorkLogController extends ApiController
{
    /**
     * GET /api/v1/team/work-logs
     * Get active work log session and completed history.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $activeLog = $user->workLogs()
            ->where('status', 'in_progress')
            ->latest()
            ->first();

        $history = $user->workLogs()
            ->where('status', 'completed')
            ->latest()
            ->paginate(10);

        return $this->sendResponse([
            'active_session' => $activeLog,
            'history'        => $history,
        ], 'Work logs retrieved successfully.');
    }

    /**
     * POST /api/v1/team/work-logs/check-in
     * Start a new work session.
     *
     * Body: { "planned_tasks": "...", "resources_needed": "..." }
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'planned_tasks'    => 'required|string',
            'resources_needed' => 'nullable|string',
        ]);

        if ($request->user()->workLogs()->where('status', 'in_progress')->exists()) {
            return $this->sendError('You already have an active work session.', 400);
        }

        $log = $request->user()->workLogs()->create([
            'start_time'       => now(),
            'planned_tasks'    => $request->planned_tasks,
            'resources_needed' => $request->resources_needed,
            'status'           => 'in_progress',
        ]);

        return $this->sendResponse($log, 'Check-in successful.', 201);
    }

    /**
     * PUT /api/v1/team/work-logs/{id}/check-out
     * End an active work session.
     *
     * Body: { "actual_tasks": "...", "challenges": "...", "kpi_metrics": [] }
     */
    public function checkOut(Request $request, $id)
    {
        $request->validate([
            'actual_tasks' => 'required|string',
            'challenges'   => 'nullable|string',
            'kpi_metrics'  => 'nullable|array',
        ]);

        $workLog = WorkLog::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->where('status', 'in_progress')
            ->first();

        if (!$workLog) {
            return $this->sendError('Active work session not found.', 404);
        }

        $workLog->update([
            'end_time'     => now(),
            'actual_tasks' => $request->actual_tasks,
            'challenges'   => $request->challenges,
            'kpi_metrics'  => $request->kpi_metrics,
            'status'       => 'completed',
        ]);

        return $this->sendResponse($workLog, 'Check-out successful.');
    }
}
