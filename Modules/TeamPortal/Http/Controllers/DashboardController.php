<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isProjectManager()) {
            return redirect()->route('team.pm.dashboard');
        }

        if ($user->isBdm()) {
            return redirect()->route('team.bdm.dashboard');
        }
        
        $stats = [
            'pending_tasks' => Task::where('assigned_to', $user->id)->whereIn('status', ['todo', 'in_progress'])->count(),
            'completed_tasks' => Task::where('assigned_to', $user->id)->where('status', 'done')->count(),
            'total_tasks' => Task::where('assigned_to', $user->id)->count(),
        ];

        // Chart 1: Task distribution by status
        $taskDistribution = [
            'labels' => ['Todo', 'In Progress', 'Review', 'Done'],
            'data' => [
                Task::where('assigned_to', $user->id)->where('status', 'todo')->count(),
                Task::where('assigned_to', $user->id)->where('status', 'in_progress')->count(),
                Task::where('assigned_to', $user->id)->where('status', 'review')->count(),
                Task::where('assigned_to', $user->id)->where('status', 'done')->count(),
            ]
        ];

        // Chart 2: Recent task completion trend (last 7 days)
        $dates = [];
        $counts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('D');
            $counts[] = Task::where('assigned_to', $user->id)
                ->where('status', 'done')
                ->whereDate('updated_at', $date)
                ->count();
        }
        
        $completionTrend = [
            'labels' => $dates,
            'data' => $counts
        ];

        $recentTasks = Task::with('project')
            ->where('assigned_to', $user->id)
            ->whereIn('status', ['todo', 'in_progress', 'review'])
            ->latest()
            ->take(5)
            ->get();

        $today = now()->format('Y-m-d');
        $attendanceToday = \Modules\ProjectManagement\Models\Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        return view('teamportal::dashboard.index', compact('stats', 'recentTasks', 'attendanceToday', 'taskDistribution', 'completionTrend'));
    }

    public function accountStatus()
    {
        $user = auth()->user();
        $status = $user->status;
        
        $message = match($status) {
            'suspended' => \Modules\Settings\Models\Setting::get('suspended_message', 'Your account has been suspended. Please contact the administrator for more information.'),
            'inactive' => \Modules\Settings\Models\Setting::get('inactive_message', 'Your account is currently inactive. This may be due to termination or pending activation.'),
            default => 'Your account access is restricted.'
        };

        return view('teamportal::account-status', compact('status', 'message', 'user'));
    }
}
