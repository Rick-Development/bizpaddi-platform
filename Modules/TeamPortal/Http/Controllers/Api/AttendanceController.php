<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Attendance;

class AttendanceController extends ApiController
{
    /**
     * GET /api/v1/team/attendance
     * Get attendance records for the authenticated team member.
     */
    public function index(Request $request)
    {
        $records = Attendance::where('user_id', $request->user()->id)
            ->latest('date')
            ->paginate(20);

        $today = Attendance::where('user_id', $request->user()->id)
            ->where('date', now()->format('Y-m-d'))
            ->first();

        return $this->sendResponse([
            'today'   => $today,
            'records' => $records,
        ], 'Attendance records retrieved successfully.');
    }

    /**
     * POST /api/v1/team/attendance/clock-in
     * Clock in for the day.
     */
    public function clockIn(Request $request)
    {
        $today    = now()->format('Y-m-d');
        $existing = Attendance::where('user_id', $request->user()->id)
            ->where('date', $today)
            ->first();

        if ($existing && $existing->clock_in) {
            return $this->sendError('You have already clocked in today.', 400);
        }

        $attendance = Attendance::updateOrCreate(
            ['user_id' => $request->user()->id, 'date' => $today],
            ['clock_in' => now()->format('H:i:s')]
        );

        return $this->sendResponse($attendance, 'Clocked in successfully.', 201);
    }

    /**
     * POST /api/v1/team/attendance/clock-out
     * Clock out for the day.
     */
    public function clockOut(Request $request)
    {
        $today      = now()->format('Y-m-d');
        $attendance = Attendance::where('user_id', $request->user()->id)
            ->where('date', $today)
            ->whereNotNull('clock_in')
            ->first();

        if (!$attendance) {
            return $this->sendError('No clock-in record found for today.', 404);
        }

        if ($attendance->clock_out) {
            return $this->sendError('You have already clocked out today.', 400);
        }

        $attendance->update(['clock_out' => now()->format('H:i:s')]);

        return $this->sendResponse($attendance, 'Clocked out successfully.');
    }
}
