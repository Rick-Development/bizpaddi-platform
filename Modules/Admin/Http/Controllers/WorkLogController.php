<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\TeamPortal\Models\WorkLog;
use Modules\ProjectManagement\Models\Task;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class WorkLogController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkLog::with(['user'])->latest('start_time');

        // Filter by Date
        if ($request->filled('date')) {
            $date = Carbon::parse($request->date);
            $query->whereDate('start_time', $date);
        }

        // Filter by User
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $workLogs = $query->paginate(20)->withQueryString();
        // Only active team members
        $users = User::where('status', 'active')->orderBy('name')->get();

        return view('admin::work-logs.index', compact('workLogs', 'users'));
    }

    public function show(WorkLog $workLog)
    {
        $workLog->load(['user']);
        return view('admin::work-logs.show', compact('workLog'));
    }

    public function exportPage(Request $request)
    {
        // All users for individual selection (including suspended/inactive)
        $allUsers = User::orderBy('name')->get();
        // Only active team members shown in simple filter
        $users = $allUsers->where('status', 'active')->values();
        return view('admin::work-logs.export-builder', compact('users', 'allUsers'));
    }

    public function exportPdf(Request $request)
    {
        $period = $request->input('period', 'daily');
        $query = WorkLog::with(['user'])->latest('start_time');

        $userFilter = null;
        $scopeGroup = null;
        $userId = $request->input('user_id', '');

        if ($userId === 'group_active') {
            $scopeGroup = 'active';
        } elseif ($userId === 'group_suspended') {
            $scopeGroup = 'suspended';
        } elseif ($userId === 'group_inactive') {
            $scopeGroup = 'inactive';
        } elseif (!empty($userId) && is_numeric($userId)) {
            $query->where('user_id', $userId);
            $userFilter = User::find($userId);
        }
        // If scopeGroup is set, filter work logs by that user group
        if ($scopeGroup) {
            $groupUserIds = User::where('status', $scopeGroup)->pluck('id');
            $query->whereIn('user_id', $groupUserIds);
        }

        $periodLabel = '';
        $dateLabel = '';

        if ($period === 'custom' && ($request->filled('start_date') || $request->filled('end_date'))) {
            $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : Carbon::minValue();
            $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();
            $query->whereBetween('start_time', [$startDate, $endDate]);
            $periodLabel = 'Custom Range Work Session & Project Performance Report';
            $dateLabel = Carbon::parse($request->start_date)->format('M d, Y') . ' - ' . Carbon::parse($request->end_date)->format('M d, Y');
        } elseif ($period === 'daily') {
            // Use start_date if provided (from export builder), else fall back to date param, else today
            $refDate = $request->filled('start_date') ? Carbon::parse($request->start_date)
                : ($request->filled('date') ? Carbon::parse($request->date) : Carbon::today());
            $query->whereDate('start_time', $refDate);
            $periodLabel = 'Daily Work Session & Project Performance Report';
            $dateLabel = $refDate->format('F d, Y');
        } elseif ($period === 'weekly') {
            $refDate = $request->filled('start_date') ? Carbon::parse($request->start_date)
                : ($request->filled('date') ? Carbon::parse($request->date) : Carbon::now());
            $startOfWeek = $refDate->copy()->startOfWeek();
            $endOfWeek = $refDate->copy()->endOfWeek();
            $query->whereBetween('start_time', [$startOfWeek, $endOfWeek]);
            $periodLabel = 'Weekly Work Session & Project Performance Report';
            $dateLabel = $startOfWeek->format('M d, Y') . ' - ' . $endOfWeek->format('M d, Y');
        } elseif ($period === 'monthly') {
            $refDate = $request->filled('start_date') ? Carbon::parse($request->start_date)
                : ($request->filled('date') ? Carbon::parse($request->date) : Carbon::now());
            $startOfMonth = $refDate->copy()->startOfMonth();
            $endOfMonth = $refDate->copy()->endOfMonth();
            $query->whereBetween('start_time', [$startOfMonth, $endOfMonth]);
            $periodLabel = 'Monthly Work Session & Project Performance Report';
            $dateLabel = $startOfMonth->format('F Y');
        } elseif ($period === 'yearly') {
            $refDate = $request->filled('start_date') ? Carbon::parse($request->start_date)
                : ($request->filled('date') ? Carbon::parse($request->date) : Carbon::now());
            $startOfYear = $refDate->copy()->startOfYear();
            $endOfYear = $refDate->copy()->endOfYear();
            $query->whereBetween('start_time', [$startOfYear, $endOfYear]);
            $periodLabel = 'Yearly Work Session & Project Performance Report';
            $dateLabel = $startOfYear->format('Y');
        }

        $workLogs = $query->get();

        // Target Users List — resolved from scope or individual filter
        if ($userFilter) {
            $targetUsers = collect([$userFilter]);
        } elseif ($scopeGroup) {
            $targetUsers = User::where('status', $scopeGroup)->orderBy('name')->get();
        } else {
            // Default: all users regardless of status (general corporate report)
            $targetUsers = User::where('status', 'active')->orderBy('name')->get();
        }

        // Section Toggles
        $includeKpis = $request->boolean('include_kpis', true);
        $includePlanned = $request->boolean('include_planned', true);
        $includeAccomplishments = $request->boolean('include_accomplishments', true);
        $includeChallenges = $request->boolean('include_challenges', true);
        $includePerformance = $request->boolean('include_performance', true);

        // Calculate Work Session Metrics (including live running session elapsed minutes)
        $totalSessions = $workLogs->count();
        $completedSessions = $workLogs->where('status', 'completed')->count();
        $inProgressSessions = $workLogs->where('status', 'in_progress')->count();

        $totalMinutes = $workLogs->sum(function($log) {
            if ($log->duration_minutes) {
                return $log->duration_minutes;
            }
            // For active running sessions without checkout, compute live elapsed minutes
            return $log->start_time ? $log->start_time->diffInMinutes(now()) : 0;
        });

        $totalHours = floor($totalMinutes / 60);
        $remainingMins = $totalMinutes % 60;
        $uniqueUsersCount = $workLogs->pluck('user_id')->unique()->count();
        $challengesCount = $workLogs->whereNotNull('challenges')->where('challenges', '!=', '')->count();

        // Calculate Task Performance & Project Metrics Per Active User
        $userTaskMetrics = [];
        $overallTotalTasks = 0;
        $overallCompletedTasks = 0;
        $overallPendingTasks = 0;
        $overallOverdueTasks = 0;

        foreach ($targetUsers as $user) {
            // All tasks assigned to user (for KPI counts)
            $allUserTasks = Task::with('project')->where('assigned_to', $user->id)->get();
            $totalTasks = $allUserTasks->count();
            $completedTasks = $allUserTasks->whereIn('status', ['done', 'completed'])->count();
            $pendingTasks = $allUserTasks->whereIn('status', ['todo', 'in_progress', 'pending', 'review'])->count();
            $overdueTasks = $allUserTasks->filter(function($t) {
                return !in_array($t->status, ['done', 'completed']) && $t->due_date && $t->due_date->isPast();
            })->count();
            $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

            // Task LIST for display: only show pending & overdue tasks (never show completed ones)
            $userTasks = $allUserTasks->filter(function($t) {
                return !in_array($t->status, ['done', 'completed']);
            })->values();

            // Unique Projects from ALL tasks (not just pending)
            $projectsList = $allUserTasks->pluck('project.title')->filter()->unique()->values()->toArray();

            // Work logs for this user strictly in the selected date range
            $userLogs = $workLogs->where('user_id', $user->id);
            
            // Include live running session minutes for user
            $userTotalMins = $userLogs->sum(function($log) {
                if ($log->duration_minutes) {
                    return $log->duration_minutes;
                }
                return $log->start_time ? $log->start_time->diffInMinutes(now()) : 0;
            });

            $userTaskMetrics[$user->id] = [
                'user' => $user,
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completedTasks,
                'pending_tasks' => $pendingTasks,
                'overdue_tasks' => $overdueTasks,
                'completion_rate' => $completionRate,
                'projects' => $projectsList,
                'tasks' => $userTasks,
                'total_sessions' => $userLogs->count(),
                'total_hours' => floor($userTotalMins / 60),
                'total_mins' => $userTotalMins % 60,
                'logs' => $userLogs
            ];

            $overallTotalTasks += $totalTasks;
            $overallCompletedTasks += $completedTasks;
            $overallPendingTasks += $pendingTasks;
            $overallOverdueTasks += $overdueTasks;
        }

        $overallCompletionRate = $overallTotalTasks > 0 ? round(($overallCompletedTasks / $overallTotalTasks) * 100, 1) : 0;

        // Group Work Logs by User
        $groupedLogs = $workLogs->groupBy('user_id');

        $pdf = Pdf::loadView('admin::work-logs.export-pdf', compact(
            'workLogs',
            'groupedLogs',
            'targetUsers',
            'userTaskMetrics',
            'period',
            'periodLabel',
            'dateLabel',
            'userFilter',
            'totalSessions',
            'completedSessions',
            'inProgressSessions',
            'totalMinutes',
            'totalHours',
            'remainingMins',
            'uniqueUsersCount',
            'challengesCount',
            'includeKpis',
            'includePlanned',
            'includeAccomplishments',
            'includeChallenges',
            'includePerformance',
            'overallTotalTasks',
            'overallCompletedTasks',
            'overallPendingTasks',
            'overallOverdueTasks',
            'overallCompletionRate'
        ));

        $pdf->setPaper('a4', 'portrait');

        $filename = strtolower(str_replace([' ', '/'], '_', $periodLabel)) . '_' . Carbon::now()->format('Y_m_d_His') . '.pdf';
        
        if ($request->boolean('preview', false)) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    public function exportSinglePdf(WorkLog $workLog)
    {
        $workLog->load(['user']);

        $userTasks = Task::with('project')->where('assigned_to', $workLog->user_id)->get();
        $totalTasks = $userTasks->count();
        $completedTasks = $userTasks->whereIn('status', ['done', 'completed'])->count();
        $pendingTasks = $userTasks->whereIn('status', ['todo', 'in_progress', 'pending', 'review'])->count();
        $overdueTasks = $userTasks->filter(function($t) {
            return !in_array($t->status, ['done', 'completed']) && $t->due_date && $t->due_date->isPast();
        })->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;
        $projectsList = $userTasks->pluck('project.title')->filter()->unique()->values()->toArray();

        $taskMetrics = [
            'total' => $totalTasks,
            'completed' => $completedTasks,
            'pending' => $pendingTasks,
            'overdue' => $overdueTasks,
            'rate' => $completionRate,
            'projects' => $projectsList,
            'tasks' => $userTasks
        ];

        $pdf = Pdf::loadView('admin::work-logs.single-pdf', compact('workLog', 'taskMetrics'));
        $pdf->setPaper('a4', 'portrait');

        $filename = 'work_session_report_#' . $workLog->id . '_' . Carbon::now()->format('Y_m_d') . '.pdf';
        return $pdf->download($filename);
    }

    public function exportCsv(Request $request)
    {
        $period = $request->input('period', 'daily');
        $query = WorkLog::with(['user'])->latest('start_time');

        $csvUserId = $request->input('user_id', '');
        if ($csvUserId === 'group_active') {
            $query->whereIn('user_id', User::where('status', 'active')->pluck('id'));
        } elseif ($csvUserId === 'group_suspended') {
            $query->whereIn('user_id', User::where('status', 'suspended')->pluck('id'));
        } elseif ($csvUserId === 'group_inactive') {
            $query->whereIn('user_id', User::where('status', 'inactive')->pluck('id'));
        } elseif (!empty($csvUserId) && is_numeric($csvUserId)) {
            $query->where('user_id', $csvUserId);
        }

        if ($period === 'custom' && ($request->filled('start_date') || $request->filled('end_date'))) {
            $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : Carbon::minValue();
            $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();
            $query->whereBetween('start_time', [$startDate, $endDate]);
        } elseif ($period === 'daily') {
            $refDate = $request->filled('start_date') ? Carbon::parse($request->start_date)
                : ($request->filled('date') ? Carbon::parse($request->date) : Carbon::today());
            $query->whereDate('start_time', $refDate);
        } elseif ($period === 'weekly') {
            $refDate = $request->filled('start_date') ? Carbon::parse($request->start_date)
                : ($request->filled('date') ? Carbon::parse($request->date) : Carbon::now());
            $query->whereBetween('start_time', [$refDate->copy()->startOfWeek(), $refDate->copy()->endOfWeek()]);
        } elseif ($period === 'monthly') {
            $refDate = $request->filled('start_date') ? Carbon::parse($request->start_date)
                : ($request->filled('date') ? Carbon::parse($request->date) : Carbon::now());
            $query->whereBetween('start_time', [$refDate->copy()->startOfMonth(), $refDate->copy()->endOfMonth()]);
        } elseif ($period === 'yearly') {
            $refDate = $request->filled('start_date') ? Carbon::parse($request->start_date)
                : ($request->filled('date') ? Carbon::parse($request->date) : Carbon::now());
            $query->whereBetween('start_time', [$refDate->copy()->startOfYear(), $refDate->copy()->endOfYear()]);
        }

        $workLogs = $query->get();

        $filename = 'work_session_report_' . $period . '_' . Carbon::now()->format('Y_m_d_His') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($workLogs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID', 'User Name', 'Email', 'Date', 'Start Time', 'End Time', 
                'Duration (Mins)', 'Status', 'Assigned Projects',
                'Assigned Tasks Total', 'Completed Tasks', 'Pending Tasks', 'Overdue Tasks', 'Completion Rate (%)',
                'Planned Tasks', 'Actual Accomplishments', 'Challenges'
            ]);

            foreach ($workLogs as $log) {
                // Ignore suspended users
                if ($log->user && $log->user->status !== 'active') {
                    continue;
                }

                $userTasks = Task::with('project')->where('assigned_to', $log->user_id)->get();
                $totalT = $userTasks->count();
                $compT = $userTasks->whereIn('status', ['done', 'completed'])->count();
                $pendT = $userTasks->whereIn('status', ['todo', 'in_progress', 'pending', 'review'])->count();
                $overT = $userTasks->filter(function($t) {
                    return !in_array($t->status, ['done', 'completed']) && $t->due_date && $t->due_date->isPast();
                })->count();
                $rateT = $totalT > 0 ? round(($compT / $totalT) * 100, 1) : 0;
                $projectsStr = $userTasks->pluck('project.title')->filter()->unique()->implode(', ');

                $durationMins = $log->duration_minutes ?? ($log->start_time ? $log->start_time->diffInMinutes(now()) : 0);

                fputcsv($file, [
                    $log->id,
                    $log->user->name ?? 'Unknown',
                    $log->user->email ?? 'N/A',
                    $log->start_time ? $log->start_time->format('Y-m-d') : 'N/A',
                    $log->start_time ? $log->start_time->format('H:i:s') : 'N/A',
                    $log->end_time ? $log->end_time->format('H:i:s') : 'Active (Running)',
                    $durationMins,
                    ucfirst($log->status ?? 'in_progress'),
                    $projectsStr,
                    $totalT,
                    $compT,
                    $pendT,
                    $overT,
                    $rateT . '%',
                    $log->planned_tasks,
                    $log->actual_tasks,
                    $log->challenges,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
