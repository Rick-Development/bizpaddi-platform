<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function clockIn(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today()->format('Y-m-d');

        // Check if already clocked in today
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($attendance) {
            return back()->with('error', 'You have already clocked in today.');
        }

        Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'clock_in' => now(),
            'status' => 'present',
            'work_mode' => 'remote', // Default to remote, could be dynamic later
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'You have successfully clocked in at ' . now()->format('H:i'));
    }

    public function clockOut(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today()->format('Y-m-d');

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return back()->with('error', 'You have not clocked in today yet.');
        }

        if ($attendance->clock_out) {
            return back()->with('error', 'You have already clocked out today.');
        }

        $clockOut = now();
        $duration = $attendance->clock_in->diffInMinutes($clockOut);

        $attendance->update([
            'clock_out' => $clockOut,
            'duration_minutes' => $duration,
        ]);

        return back()->with('success', 'You have successfully clocked out. Total time: ' . floor($duration / 60) . 'h ' . ($duration % 60) . 'm');
    }
}
