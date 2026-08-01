<?php

namespace Modules\TeamPortal\Http\Controllers\PM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Project;
use Modules\ProjectManagement\Models\Task;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Keep the PM dashboard usable even when imported projects are not yet linked by manager_id.
        $projects = Project::with(['client', 'tasks', 'teamMembers'])
            ->manageableBy($user)
            ->get();

        // Calculate stats
        $stats = [
            'active_projects' => $projects->where('status', 'active')->count(),
            'total_projects' => $projects->count(),
            'total_team_members' => $projects->flatMap->teamMembers->unique('id')->count(),
            'pending_tasks' => Task::whereIn('project_id', $projects->pluck('id'))
                ->whereIn('status', ['todo', 'in_progress'])
                ->count(),
            'overdue_tasks' => Task::whereIn('project_id', $projects->pluck('id'))
                ->where('status', '!=', 'done')
                ->where('due_date', '<', now())
                ->count(),
            'completed_this_week' => Task::whereIn('project_id', $projects->pluck('id'))
                ->where('status', 'done')
                ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
        ];

        // Get recent activity (last 10 tasks updated)
        $recentActivity = Task::with(['project', 'assignee'])
            ->whereIn('project_id', $projects->pluck('id'))
            ->latest('updated_at')
            ->take(10)
            ->get();

        // Project health indicators
        $projectHealth = $projects->map(function($project) {
            $totalTasks = $project->tasks->count();
            $completedTasks = $project->tasks->where('status', 'done')->count();
            $overdueTasks = $project->tasks->where('status', '!=', 'done')
                ->where('due_date', '<', now())->count();
            
            $completion = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
            
            // Determine health status
            if ($overdueTasks > 3 || $completion < 30) {
                $health = 'critical';
            } elseif ($overdueTasks > 0 || $completion < 60) {
                $health = 'warning';
            } else {
                $health = 'good';
            }
            
            return [
                'project' => $project,
                'completion' => $completion,
                'overdue_tasks' => $overdueTasks,
                'health' => $health,
            ];
        });

        return view('teamportal::pm.dashboard', compact('stats', 'projects', 'recentActivity', 'projectHealth'));
    }
}
