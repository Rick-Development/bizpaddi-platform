<?php

namespace Modules\Admin\Http\Controllers\Api\PM;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Api\ApiController;
use Modules\TeamPortal\Models\WorkLog;

class WorkLogController extends ApiController
{
    /**
     * GET /api/v1/admin/pm/work-logs
     */
    public function index(Request $request)
    {
        $query = WorkLog::with('user')->latest();

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $workLogs = $query->paginate(30);
        return $this->sendResponse($workLogs, 'Work logs retrieved successfully.');
    }

    /**
     * GET /api/v1/admin/pm/work-logs/{id}
     */
    public function show($id)
    {
        $workLog = WorkLog::with('user')->find($id);
        if (!$workLog) return $this->sendError('Work log not found.', 404);
        return $this->sendResponse($workLog, 'Work log details retrieved successfully.');
    }

    /**
     * PUT /api/v1/admin/pm/work-logs/{id}
     * Admin override — e.g. set end_time for a team member who forgot to check out.
     * duration_minutes is auto-calculated from start_time and end_time via model boot.
     */
    public function update(Request $request, $id)
    {
        $workLog = WorkLog::with('user')->find($id);
        if (!$workLog) return $this->sendError('Work log not found.', 404);

        // Prevent updating someone else's user_id — admin corrects time, not ownership
        $validated = $request->validate([
            'end_time'         => 'sometimes|date|after:start_time',
            'start_time'       => 'sometimes|date',
            'planned_tasks'    => 'nullable|string',
            'resources_needed' => 'nullable|string',
            'actual_tasks'     => 'nullable|string',
            'challenges'       => 'nullable|string',
            'kpi_metrics'      => 'nullable|array',
            'status'           => 'nullable|string',
        ]);

        $workLog->update($validated);
        // duration_minutes is auto-recalculated by model boot on save

        $workLog->refresh();

        return $this->sendResponse([
            'id'               => $workLog->id,
            'user'             => $workLog->user,
            'start_time'       => $workLog->start_time,
            'end_time'         => $workLog->end_time,
            'duration_minutes' => $workLog->duration_minutes,
            'status'           => $workLog->status,
        ], 'Work log updated successfully. Duration recalculated automatically.');
    }
}
