<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Admin;
use Modules\ProjectManagement\Models\Project;
use Modules\ProjectManagement\Models\Task;
use Modules\TeamPortal\Models\WorkLog;
use Modules\Contact\Models\Lead;
use Modules\Accounting\Models\Invoice;
use Modules\ProjectManagement\Services\PerformanceEngine;
use App\Notifications\OperationalMailNotification;
use Carbon\Carbon;

class SendOperationalReports extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'reports:send-operational {--type= : Force a specific report type (daily, weekly, all, performance)}';

    /**
     * The console command description.
     */
    protected $description = 'Generate and send daily operational, PM accountability, and weekly CEO executive reports.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Use Nigeria / West African Time (WAT) for time checks
        $now = Carbon::now('Africa/Lagos');
        $hour = $now->hour;
        $dayOfWeek = $now->dayOfWeek; // Carbon::MONDAY = 1
        $forceType = $this->option('type');

        $runPerformance = !$forceType || in_array($forceType, ['performance', 'all']);
        $runDaily = ($hour === 19) || in_array($forceType, ['daily', 'all']);
        $runWeekly = ($dayOfWeek === Carbon::MONDAY && $hour === 8) || in_array($forceType, ['weekly', 'all']);

        $this->info("Running reports generator. Force Type: " . ($forceType ?: 'None'));

        $startOfMonth = Carbon::now('Africa/Lagos')->startOfMonth();
        $endOfMonth = Carbon::now('Africa/Lagos')->endOfMonth();

        // Target PMs and Admins
        $pms = User::where('status', 'active')
            ->whereIn('role', ['pm', 'project_manager', 'Project Manager'])
            ->get();

        $admins = Admin::where('is_active', true)->get();

        // 1. Performance Warnings (Daily - Check if user score is below 70%)
        if ($runPerformance) {
            $this->info('Checking performance scorecards...');
            $team = User::where('status', 'active')
                ->whereIn('role', ['developer', 'project_manager', 'sales_marketing'])
                ->get();

            foreach ($team as $member) {
                $performance = PerformanceEngine::calculate($member->id, $startOfMonth, $now);
                $score = $performance['score'] ?? 100;
                if ($score < 70) {
                    $member->notify(new OperationalMailNotification('Performance Warning', [
                        'name' => $member->name,
                        'score' => $score,
                        'performance_url' => route('team.kpis.index'), // Assuming KPIs page is scorecard
                    ]));
                }
            }
        }

        // ==========================================
        // DAILY REPORTS
        // ==========================================
        if ($runDaily) {
            $this->info('Generating daily reports...');

            // A. Admin Daily Report
            $newLeadsCount = Lead::whereDate('created_at', Carbon::today())->count();
            $invoicesRaised = Invoice::whereDate('created_at', Carbon::today())->count();
            // Try fetching support ticket model dynamically or default to 0
            $openTicketsCount = 0;
            try {
                $openTicketsCount = \Modules\ClientPortal\Models\SupportTicket::where('status', 'open')->count();
            } catch (\Exception $e) {}
            $activeClockIns = WorkLog::whereDate('start_time', Carbon::today())->count();

            foreach ($admins as $admin) {
                $admin->notify(new OperationalMailNotification('Admin Daily Report', [
                    'new_leads' => $newLeadsCount,
                    'invoices_count' => $invoicesRaised,
                    'open_tickets' => $openTicketsCount,
                    'active_clock_ins' => $activeClockIns,
                ]));
            }

            // B. Daily Team Performance Summary (Attendance stats)
            $todayLogs = WorkLog::whereDate('start_time', Carbon::today())->get();
            $totalCount = $team->count();
            $onTimeCount = 0;
            $lateCount = 0;
            $absentCount = 0;

            foreach ($team as $member) {
                $log = $todayLogs->where('user_id', $member->id)->first();
                if (!$log) {
                    $absentCount++;
                } else {
                    // Assume check-in target is before 9:00 AM
                    $checkinTime = Carbon::parse($log->start_time);
                    if ($checkinTime->hour >= 9) {
                        $lateCount++;
                    } else {
                        $onTimeCount++;
                    }
                }
            }

            foreach ($pms as $pm) {
                $pm->notify(new OperationalMailNotification('Daily Team Performance Summary', [
                    'total_members' => $totalCount,
                    'on_time' => $onTimeCount,
                    'late' => $lateCount,
                    'absent' => $absentCount,
                    'admin_url' => route('admin.work-logs.index'),
                ]));
            }

            // C. Daily Project Performance Summary (Project manager-specific)
            foreach ($pms as $pm) {
                $managedProjects = Project::where('manager_id', $pm->id)->get();
                $projectsSummaryHtml = '';

                foreach ($managedProjects as $project) {
                    $totalTasks = $project->tasks()->count();
                    $doneTasks = $project->tasks()->where('status', 'done')->count();
                    $progress = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 100;
                    $projectsSummaryHtml .= "<li><strong>{$project->name}</strong>: Progress {$progress}%, Tasks completed today: " . $project->tasks()->whereDate('completed_at', Carbon::today())->count() . "</li>";
                }

                $pm->notify(new OperationalMailNotification('Daily Project Performance Summary', [
                    'projects_summary' => empty($projectsSummaryHtml) ? '<li>No active projects under management.</li>' : $projectsSummaryHtml,
                ]));
            }

            $this->info('Daily reports dispatched.');
        }

        // ==========================================
        // WEEKLY REPORTS
        // ==========================================
        if ($runWeekly) {
            $this->info('Generating weekly executive and project reports...');

            $startOfWeek = Carbon::now('Africa/Lagos')->subWeek()->startOfWeek();
            $endOfWeek = Carbon::now('Africa/Lagos')->subWeek()->endOfWeek();

            // A. Weekly CEO Executive Summary & Admin Weekly Report
            $completedProjectsCount = Project::whereBetween('completed_at', [$startOfWeek, $endOfWeek])->count();
            $activeProjectsCount = Project::where('status', 'active')->count();
            $invoicesRaisedTotal = Invoice::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('total_amount') ?: 0;
            $newLeadsWeekly = Lead::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
            
            // Average team scorecard score
            $totalScore = 0;
            $scoreCount = 0;
            foreach ($team as $member) {
                $perf = PerformanceEngine::calculate($member->id, $startOfWeek, $endOfWeek);
                $totalScore += $perf['score'] ?? 100;
                $scoreCount++;
            }
            $averageTeamScore = $scoreCount > 0 ? round($totalScore / $scoreCount, 1) : 100;

            // Notify CEO / Admins
            foreach ($admins as $admin) {
                $admin->notify(new OperationalMailNotification('Weekly CEO Executive Summary', [
                    'projects_status' => "{$completedProjectsCount} completed, {$activeProjectsCount} active",
                    'invoices_total' => '$' . number_format($invoicesRaisedTotal, 2),
                    'leads_count' => $newLeadsWeekly,
                    'avg_score' => $averageTeamScore,
                    'admin_url' => route('admin.dashboard'),
                ]));

                $admin->notify(new OperationalMailNotification('Admin Weekly Report', [
                    'invoices_total' => '$' . number_format($invoicesRaisedTotal, 2),
                    'clients_count' => User::where('role', 'client')->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count(),
                    'resolved_tickets' => 0, // Fallback if no ticket table exists
                    'average_team_score' => $averageTeamScore,
                ]));
            }

            // B. Weekly PM Reports (Team & Project reports)
            foreach ($pms as $pm) {
                // Team Performance Scorecard table rows
                $teamRowsHtml = '';
                $managedProjects = Project::where('manager_id', $pm->id)->get();
                
                // Find developers working on PM's projects
                $pmTeamUserIds = \DB::table('project_team_members')
                    ->whereIn('project_id', $managedProjects->pluck('id'))
                    ->pluck('user_id')
                    ->unique();

                $pmTeam = User::whereIn('id', $pmTeamUserIds)->where('status', 'active')->get();

                foreach ($pmTeam as $member) {
                    $perf = PerformanceEngine::calculate($member->id, $startOfWeek, $endOfWeek);
                    $teamRowsHtml .= "<tr><td>{$member->name}</td><td>{$perf['tasks']['completed']}</td><td>{$perf['violations']['failed_checkout_count']}</td><td>{$perf['score']}%</td></tr>";
                }

                $pm->notify(new OperationalMailNotification('Weekly Team Performance Report', [
                    'table_rows' => empty($teamRowsHtml) ? '<tr><td colspan="4">No team members assigned to projects.</td></tr>' : $teamRowsHtml,
                ]));

                // Project Status Audit List
                $projectsAuditHtml = '';
                foreach ($managedProjects as $project) {
                    $totalTasks = $project->tasks()->count();
                    $doneTasks = $project->tasks()->where('status', 'done')->count();
                    $overdueTasks = $project->tasks()->where('status', '!=', 'done')->where('due_date', '<', Carbon::now())->count();
                    $projectsAuditHtml .= "<li><strong>{$project->name}</strong>: {$doneTasks}/{$totalTasks} tasks completed. Overdue: {$overdueTasks}</li>";
                }

                $pm->notify(new OperationalMailNotification('Weekly Project Performance Report', [
                    'projects_audit' => empty($projectsAuditHtml) ? '<li>No managed projects found.</li>' : $projectsAuditHtml,
                ]));

                // PM Weekly Accountability Scorecard
                $pmResponseTimeHours = 12; // Arbitrary target or log time tracking
                $pendingReviews = Task::whereIn('project_id', $managedProjects->pluck('id'))->where('status', 'review')->count();
                $resolvedBlockers = Task::whereIn('project_id', $managedProjects->pluck('id'))->where('status', 'done')->whereNotNull('completed_at')->count();
                $overdueTasksCount = Task::whereIn('project_id', $managedProjects->pluck('id'))->where('status', '!=', 'done')->where('due_date', '<', Carbon::now())->count();

                $pm->notify(new OperationalMailNotification('PM Weekly Accountability Report', [
                    'pm_name' => $pm->name,
                    'projects_count' => $managedProjects->count(),
                    'avg_response_hours' => $pmResponseTimeHours,
                    'pending_reviews' => $pendingReviews,
                    'resolved_blockers' => $resolvedBlockers,
                    'overdue_count' => $overdueTasksCount,
                ]));
            }

            $this->info('Weekly executive and PM reports dispatched.');
        }

        return Command::SUCCESS;
    }
}
