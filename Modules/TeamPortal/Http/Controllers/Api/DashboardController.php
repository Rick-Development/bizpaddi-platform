<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Task;
use Modules\ProjectManagement\Models\Attendance;

class DashboardController extends ApiController
{
    /**
     * GET /api/v1/team/dashboard
     * Returns dashboard stats, charts, recent tasks, and attendance status.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $stats = [
            'pending_tasks'   => Task::where('assigned_to', $user->id)->whereIn('status', ['todo', 'in_progress'])->count(),
            'completed_tasks' => Task::where('assigned_to', $user->id)->where('status', 'done')->count(),
            'total_tasks'     => Task::where('assigned_to', $user->id)->count(),
            'in_review_tasks' => Task::where('assigned_to', $user->id)->where('status', 'review')->count(),
        ];

        // Chart: Task distribution by status
        $taskDistribution = [
            'labels' => ['Todo', 'In Progress', 'Review', 'Done'],
            'data'   => [
                Task::where('assigned_to', $user->id)->where('status', 'todo')->count(),
                Task::where('assigned_to', $user->id)->where('status', 'in_progress')->count(),
                Task::where('assigned_to', $user->id)->where('status', 'review')->count(),
                Task::where('assigned_to', $user->id)->where('status', 'done')->count(),
            ],
        ];

        // Chart: 7-day task completion trend
        $dates  = [];
        $counts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date     = now()->subDays($i)->format('Y-m-d');
            $dates[]  = now()->subDays($i)->format('D');
            $counts[] = Task::where('assigned_to', $user->id)
                ->where('status', 'done')
                ->whereDate('updated_at', $date)
                ->count();
        }

        $completionTrend = ['labels' => $dates, 'data' => $counts];

        $recentTasks = Task::with('project')
            ->where('assigned_to', $user->id)
            ->whereIn('status', ['todo', 'in_progress', 'review'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($t) => [
                'id'          => $t->id,
                'title'       => $t->title,
                'status'      => $t->status,
                'priority'    => $t->priority,
                'due_date'    => $t->due_date?->toDateString(),
                'project'     => $t->project?->only(['id', 'title']),
            ]);

        $attendanceToday = Attendance::where('user_id', $user->id)
            ->where('date', now()->format('Y-m-d'))
            ->first();

        return $this->sendResponse([
            'stats'            => $stats,
            'task_distribution' => $taskDistribution,
            'completion_trend' => $completionTrend,
            'recent_tasks'     => $recentTasks,
            'attendance_today' => $attendanceToday ? [
                'clock_in'  => $attendanceToday->clock_in,
                'clock_out' => $attendanceToday->clock_out,
                'status'    => $attendanceToday->clock_out ? 'clocked_out' : 'clocked_in',
            ] : null,
        ], 'Dashboard data retrieved successfully.');
    }
}
