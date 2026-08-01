<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\TeamPortal\Models\WorkLog;

class WorkLogController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Check for active session
        $activeLog = $user->workLogs()
                          ->where('status', 'in_progress')
                          ->latest()
                          ->first();

        // Get history
        $history = $user->workLogs()
                        ->where('status', 'completed')
                        ->latest()
                        ->paginate(10);

        return view('teamportal::work-logs.index', compact('activeLog', 'history'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'planned_tasks' => 'required|string',
            'resources_needed' => 'nullable|string',
        ]);

        // Prevent double check-in
        if (auth()->user()->workLogs()->where('status', 'in_progress')->exists()) {
             return redirect()->back()->with('error', 'You already have an active work session.');
        }

        auth()->user()->workLogs()->create([
            'start_time' => now(),
            'planned_tasks' => $request->planned_tasks,
            'resources_needed' => $request->resources_needed,
            'status' => 'in_progress',
        ]);

        return redirect()->route('team.work-logs.index')->with('success', 'You have successfully checked in.');
    }

    public function checkOut(Request $request, WorkLog $workLog)
    {
        $request->validate([
            'actual_tasks' => 'nullable|string|required_without:actual_tasks_encoded',
            'actual_tasks_encoded' => 'nullable|string|required_without:actual_tasks',
            'challenges' => 'nullable|string',
            'challenges_encoded' => 'nullable|string',
            'kpi_metrics' => 'nullable|array',
        ]);

        abort_unless($workLog->user()->is(auth()->user()), 403);
        abort_unless($workLog->status === 'in_progress', 403);

        $actualTasks = $this->decodeWorkLogField(
            $request->input('actual_tasks_encoded'),
            $request->input('actual_tasks')
        );
        $challenges = $this->decodeWorkLogField(
            $request->input('challenges_encoded'),
            $request->input('challenges')
        );

        if ($actualTasks === null || trim($actualTasks) === '') {
            return back()
                ->withErrors(['actual_tasks' => 'The work completed summary field is required.'])
                ->withInput();
        }

        $workLog->update([
            'end_time' => now(),
            'actual_tasks' => $actualTasks,
            'challenges' => $challenges,
            'kpi_metrics' => $request->kpi_metrics, // Assuming JSON input or array
            'status' => 'completed',
        ]);

        return redirect()->route('team.work-logs.index')->with('success', 'You have checked out successfully. Great work!');
    }

    private function decodeWorkLogField(?string $encodedValue, ?string $fallbackValue): ?string
    {
        if ($encodedValue === null || $encodedValue === '') {
            return $fallbackValue;
        }

        $decoded = base64_decode($encodedValue, true);

        return $decoded === false ? $fallbackValue : $decoded;
    }
}
