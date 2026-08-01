<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\TeamPortal\Models\DailyReport;

class ReportController extends ApiController
{
    /**
     * GET /api/v1/team/reports
     * List all daily reports submitted by the authenticated team member.
     */
    public function index(Request $request)
    {
        $reports = DailyReport::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return $this->sendResponse($reports, 'Reports retrieved successfully.');
    }

    /**
     * POST /api/v1/team/reports
     * Submit a new daily report.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tasks_completed' => 'required|string',
            'tasks_planned'   => 'nullable|string',
            'challenges'      => 'nullable|string',
            'content'         => 'nullable|string',
            'hours_worked'    => 'nullable|numeric|min:0|max:24',
        ]);

        $report = DailyReport::create([
            'user_id'         => $request->user()->id,
            'tasks_completed' => $request->tasks_completed,
            'tasks_planned'   => $request->tasks_planned,
            'challenges'      => $request->challenges,
            'content'         => $request->content,
            'hours_worked'    => $request->hours_worked,
        ]);

        return $this->sendResponse($report, 'Report submitted successfully.', 201);
    }

    /**
     * GET /api/v1/team/reports/{id}
     * Get details of a specific report.
     */
    public function show(Request $request, $id)
    {
        $report = DailyReport::where('user_id', $request->user()->id)->find($id);

        if (!$report) {
            return $this->sendError('Report not found.', 404);
        }

        return $this->sendResponse($report, 'Report details retrieved successfully.');
    }
}
