<?php

namespace Modules\TeamPortal\Http\Controllers\QA;

use App\Http\Controllers\Controller;
use Modules\TeamPortal\Models\BugReport;
use Modules\ProjectManagement\Models\Task;
use Modules\ProjectManagement\Models\Project;

class QaDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isAdminOrPm = $user->isAdmin() || $user->isProjectManager();

        $myBugs = BugReport::query();
        if (!$isAdminOrPm) {
            $myBugs->where(function ($q) use ($user) {
                $q->where('reported_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }

        $stats = [
            'total_bugs'       => (clone $myBugs)->count(),
            'open_bugs'        => (clone $myBugs)->where('status', 'open')->count(),
            'in_progress_bugs' => (clone $myBugs)->where('status', 'in_progress')->count(),
            'resolved_bugs'    => (clone $myBugs)->whereIn('status', ['resolved', 'closed'])->count(),
            'critical_bugs'    => (clone $myBugs)->where('severity', 'critical')->count(),
            'my_tasks'         => Task::where('assigned_to', $user->id)->whereIn('status', ['todo', 'in_progress', 'review'])->count(),
        ];

        $recentBugsQuery = BugReport::with(['project', 'assignee', 'reporter']);
        if (!$isAdminOrPm) {
            $recentBugsQuery->where(function ($q) use ($user) {
                $q->where('reported_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }
        $recentBugs = $recentBugsQuery->latest()->take(6)->get();

        // Severity breakdown for chart
        $severityChart = [
            'labels' => ['Critical', 'High', 'Medium', 'Low'],
            'data'   => [
                (clone $myBugs)->where('severity', 'critical')->count(),
                (clone $myBugs)->where('severity', 'high')->count(),
                (clone $myBugs)->where('severity', 'medium')->count(),
                (clone $myBugs)->where('severity', 'low')->count(),
            ],
        ];

        // Status breakdown for chart
        $statusChart = [
            'labels' => ['Open', 'In Progress', 'Resolved', 'Closed', "Won't Fix"],
            'data'   => [
                (clone $myBugs)->where('status', 'open')->count(),
                (clone $myBugs)->where('status', 'in_progress')->count(),
                (clone $myBugs)->where('status', 'resolved')->count(),
                (clone $myBugs)->where('status', 'closed')->count(),
                (clone $myBugs)->where('status', 'wont_fix')->count(),
            ],
        ];

        $myTasks = Task::with('project')
            ->where('assigned_to', $user->id)
            ->whereIn('status', ['todo', 'in_progress', 'review'])
            ->latest()
            ->take(5)
            ->get();

        return view('teamportal::qa.dashboard', compact(
            'stats', 'recentBugs', 'severityChart', 'statusChart', 'myTasks'
        ));
    }
}
