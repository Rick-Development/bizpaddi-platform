<?php

namespace Modules\ProjectManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\TimeLog;
use Modules\ProjectManagement\Models\Task;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TimeLogController extends Controller
{
    /**
     * Start a timer for a task.
     */
    public function start(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:pm_tasks,id',
        ]);

        $user = Auth::user();
        $userType = get_class($user);

        // Check if there's already an active timer for this user
        $activeLog = TimeLog::where('user_id', $user->id)
            ->where('user_type', $userType)
            ->whereNull('end_time')
            ->first();

        if ($activeLog) {
            return back()->with('error', 'You already have an active timer running.');
        }

        TimeLog::create([
            'task_id' => $request->task_id,
            'user_id' => $user->id,
            'user_type' => $userType,
            'start_time' => now(),
        ]);

        return back()->with('success', 'Timer started.');
    }

    /**
     * Stop the active timer.
     */
    public function stop(TimeLog $timeLog)
    {
        $user = Auth::user();
        
        // if ($timeLog->user_id !== $user->id || $timeLog->user_type !== get_class($user)) {
        //     abort(403);
        // }

        if ($timeLog->end_time) {
            return back()->with('error', 'Timer already stopped.');
        }

        $endTime = now();
        $startTime = Carbon::parse($timeLog->start_time);
        $durationMinutes = $startTime->diffInMinutes($endTime);

        $timeLog->update([
            'end_time' => $endTime,
            'duration_minutes' => $durationMinutes,
        ]);

        return back()->with('success', "Timer stopped. Duration: {$durationMinutes} minutes.");
    }

    /**
     * Store a manual time entry.
     */
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:pm_tasks,id',
            'duration_hours' => 'required|numeric|min:0',
            'duration_minutes' => 'required|numeric|min:0|max:59',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $user = Auth::user();
        $totalMinutes = ($request->duration_hours * 60) + $request->duration_minutes;
        
        $startTime = Carbon::parse($request->date)->startOfDay(); // Simplified
        $endTime = $startTime->copy()->addMinutes($totalMinutes);

        TimeLog::create([
            'task_id' => $request->task_id,
            'user_id' => $user->id,
            'user_type' => $userType = get_class($user),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_minutes' => $totalMinutes,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Time entry added manually.');
    }

    /**
     * Remove a time entry.
     */
    public function destroy(TimeLog $timeLog)
    {
        $user = Auth::user();

        // Only author or admin can delete
        if ($timeLog->user_id !== $user->id && !($user->is_admin ?? false)) {
            abort(403);
        }

        $timeLog->delete();

        return back()->with('success', 'Time entry removed.');
    }
}
