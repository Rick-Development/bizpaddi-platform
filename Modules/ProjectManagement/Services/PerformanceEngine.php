<?php

namespace Modules\ProjectManagement\Services;

use Modules\ProjectManagement\Models\Task;
use Modules\TeamPortal\Models\WorkLog;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PerformanceEngine
{
    /**
     * Calculate performance for a specific user and date range.
     */
    public static function calculate($userId, Carbon $startDate, Carbon $endDate)
    {
        $baseScore = 100.00;
        
        // 1. Task Metrics
        $tasks = Task::where('assigned_to', $userId)
            ->whereBetween('completed_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->where('status', 'done')
            ->get();

        $completedCount = $tasks->count();
        $lateCount = 0;
        $earlyCount = 0;
        $taskPenaltyPoints = 0;
        $taskBonusPoints = 0;

        foreach ($tasks as $task) {
            if ($task->due_date) {
                if ($task->completed_at > $task->due_date) {
                    $lateCount++;
                    $taskPenaltyPoints += 5.00;
                } elseif ($task->completed_at->copy()->startOfDay()->diffInDays($task->due_date->copy()->startOfDay(), false) >= 1) {
                    // Completed at least 1 full day before due date
                    $earlyCount++;
                    $taskBonusPoints += 2.00;
                }
            }
        }

        // Currently Late Tasks (Ongoing Penalities)
        // If a task is past due but not DONE, and due date is within our period or before
        $currentlyLateCount = Task::where('assigned_to', $userId)
            ->whereIn('status', ['todo', 'in_progress', 'review', 'testing']) // not done
            ->where('due_date', '<', Carbon::now())
            ->whereBetween('due_date', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->count();

        $lateCount += $currentlyLateCount;
        $taskPenaltyPoints += ($currentlyLateCount * 5.00);

        // 2. Attendance Metrics (Work Logs)
        $workLogs = WorkLog::where('user_id', $userId)
            ->whereBetween('start_time', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->get();

        $totalDurationMinutes = $workLogs->sum('duration_minutes');
        
        // Group logs by date to find days present
        $daysPresent = $workLogs->groupBy(function($log) {
            return Carbon::parse($log->start_time)->format('Y-m-d');
        })->count();

        // Calculate expected working days (Mon-Fri) in the date range, up to today
        $expectedWorkingDays = 0;
        $missedCheckinCount = 0;
        $missedCheckinPenalty = 0;
        
        $periodLimit = $endDate->isFuture() ? Carbon::today() : $endDate->copy()->endOfDay();
        
        if ($startDate->lte($periodLimit)) {
            $period = CarbonPeriod::create($startDate->copy()->startOfDay(), $periodLimit->copy()->startOfDay());
            foreach ($period as $date) {
                if (!$date->isSunday()) {
                    $expectedWorkingDays++;
                    $dateStr = $date->format('Y-m-d');
                    
                    // If they have no logs for this weekday, it's a missed check-in
                    if (!$workLogs->contains(function($log) use ($dateStr) {
                        return Carbon::parse($log->start_time)->format('Y-m-d') === $dateStr;
                    })) {
                        $missedCheckinCount++;
                        $missedCheckinPenalty += 5.00; // User said 5 points
                    }
                }
            }
        }

        // 3. Work Log Policy Violations
        $unrealisticCount = 0;
        $unrealisticPenalty = 0;
        $failedCheckoutCount = 0;
        $failedCheckoutPenalty = 0;

        foreach ($workLogs as $log) {
            // Unrealistic hours (Completed session > 14 hours)
            if ($log->status === 'completed' && $log->duration_minutes > (14 * 60)) {
                $unrealisticCount++;
                $unrealisticPenalty += 3.00; // User said 3 points
            }
            
            // Failed checkout (Force closed by system or currently running > 14 hours)
            if ($log->status === 'in_progress') {
                $runningMinutes = Carbon::parse($log->start_time)->diffInMinutes(Carbon::now());
                if ($runningMinutes > (14 * 60)) {
                    $failedCheckoutCount++;
                    $failedCheckoutPenalty += 5.00; // User said 5 points
                }
            } elseif (str_contains(strtolower($log->notes ?? ''), 'auto-checkout') || str_contains(strtolower($log->notes ?? ''), 'force closed')) {
                 $failedCheckoutCount++;
                 $failedCheckoutPenalty += 5.00;
            }
        }

        // Calculate final score
        $totalPenalty = $taskPenaltyPoints + $missedCheckinPenalty + $unrealisticPenalty + $failedCheckoutPenalty;
        $finalScore = $baseScore + $taskBonusPoints - $totalPenalty;
        $finalScore = max(0, min(150, $finalScore)); // Cap 0 - 150

        return [
            'score' => round($finalScore, 1),
            'tasks' => [
                'completed' => $completedCount,
                'early' => $earlyCount,
                'late' => $lateCount,
                'bonus_points' => $taskBonusPoints,
                'penalty_points' => $taskPenaltyPoints,
            ],
            'attendance' => [
                'days_present' => $daysPresent,
                'days_absent' => max(0, $expectedWorkingDays - $daysPresent),
                'missed_checkins' => $missedCheckinCount,
                'total_minutes' => $totalDurationMinutes,
                'avg_daily_hours' => $daysPresent > 0 ? round(($totalDurationMinutes / 60) / $daysPresent, 1) : 0,
                'expected_days' => $expectedWorkingDays,
            ],
            'violations' => [
                'unrealistic_count' => $unrealisticCount,
                'unrealistic_penalty' => $unrealisticPenalty,
                'failed_checkout_count' => $failedCheckoutCount,
                'failed_checkout_penalty' => $failedCheckoutPenalty,
                'missed_checkin_penalty' => $missedCheckinPenalty,
                'total_penalty' => $unrealisticPenalty + $failedCheckoutPenalty + $missedCheckinPenalty
            ],
            'total_bonus' => $taskBonusPoints,
            'total_penalty' => $totalPenalty
        ];
    }

    /**
     * Generate a comprehensive assessment report for a team member in a given timeframe.
     * Includes score, grade, completed tasks, pending tasks, overdue tasks, work logs, and actionable insights.
     */
    public static function getMemberAssessment($userId, Carbon $startDate, Carbon $endDate)
    {
        $user = User::findOrFail($userId);

        // 1. Calculate base metrics & scores
        $metrics = self::calculate($userId, $startDate, $endDate);
        $score = $metrics['score'];

        // 2. Grade and status
        $grade = match (true) {
            $score >= 95 => ['label' => 'Excellent', 'class' => 'emerald', 'badge' => '🏆 Excellent', 'letter' => 'A+'],
            $score >= 80 => ['label' => 'Good', 'class' => 'blue', 'badge' => '✨ Good', 'letter' => 'A'],
            $score >= 60 => ['label' => 'Fair', 'class' => 'amber', 'badge' => '⚠️ Fair', 'letter' => 'B'],
            default      => ['label' => 'Needs Improvement', 'class' => 'rose', 'badge' => '📉 Needs Improvement', 'letter' => 'C'],
        };

        // 3. Completed Tasks in this period
        $completedTasks = Task::with('project')
            ->where('assigned_to', $userId)
            ->where('status', 'done')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('completed_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
                  ->orWhere(function ($sub) use ($startDate, $endDate) {
                      $sub->whereNull('completed_at')
                          ->whereBetween('updated_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()]);
                  });
            })
            ->orderBy('completed_at', 'desc')
            ->get()
            ->map(function ($task) {
                $timing = 'on_time';
                if ($task->due_date) {
                    if ($task->completed_at && $task->completed_at > $task->due_date) {
                        $timing = 'late';
                    } elseif ($task->completed_at && $task->completed_at->copy()->startOfDay()->diffInDays($task->due_date->copy()->startOfDay(), false) >= 1) {
                        $timing = 'early';
                    }
                }
                $task->timing = $timing;
                return $task;
            });

        // 4. Pending / In-Progress Tasks (unfinished)
        $pendingTasks = Task::with('project')
            ->where('assigned_to', $userId)
            ->whereIn('status', ['todo', 'in_progress', 'review', 'testing'])
            ->orderByRaw("CASE WHEN due_date IS NOT NULL AND due_date < NOW() THEN 0 ELSE 1 END")
            ->orderBy('due_date', 'asc')
            ->get()
            ->map(function ($task) {
                $task->is_overdue = $task->due_date && $task->due_date->isPast();
                $task->days_overdue = $task->is_overdue ? (int) $task->due_date->diffInDays(now()) : 0;
                return $task;
            });

        // 5. Overdue Tasks subset
        $overdueTasks = $pendingTasks->filter(fn($t) => $t->is_overdue)->values();

        // 6. Work Logs in this period
        $workLogs = WorkLog::where('user_id', $userId)
            ->whereBetween('start_time', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->orderBy('start_time', 'desc')
            ->get();

        $totalMinutes = $workLogs->sum('duration_minutes');
        $totalHours = round($totalMinutes / 60, 1);
        $daysPresent = $workLogs->groupBy(fn($l) => Carbon::parse($l->start_time)->format('Y-m-d'))->count();

        // 7. KPIs
        $totalAssignedActive = $completedTasks->count() + $pendingTasks->count();
        $completionRate = $totalAssignedActive > 0 ? round(($completedTasks->count() / $totalAssignedActive) * 100, 1) : 0;
        $onTimeCount = $completedTasks->whereIn('timing', ['on_time', 'early'])->count();
        $onTimeRate = $completedTasks->count() > 0 ? round(($onTimeCount / $completedTasks->count()) * 100, 1) : 100;

        // 8. Generate Constructive Manager Summary
        $summaryPoints = [];
        if ($completedTasks->count() > 0) {
            $summaryPoints[] = "Completed {$completedTasks->count()} task(s) during this period with {$onTimeRate}% on-time rate.";
        } else {
            $summaryPoints[] = "No tasks were marked as completed during this evaluation period.";
        }

        if ($overdueTasks->count() > 0) {
            $summaryPoints[] = "⚠️ Attention required: {$overdueTasks->count()} pending task(s) are overdue.";
        } else {
            $summaryPoints[] = "✓ Zero overdue tasks currently pending. Excellent turnaround!";
        }

        if ($pendingTasks->count() > 0) {
            $summaryPoints[] = "Carrying over {$pendingTasks->count()} unfinished / in-progress task(s).";
        }

        if ($totalHours > 0) {
            $summaryPoints[] = "Logged {$totalHours} hours across {$daysPresent} active work day(s).";
        }

        return [
            'user'           => $user,
            'startDate'      => $startDate,
            'endDate'        => $endDate,
            'periodLabel'    => $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y'),
            'metrics'        => $metrics,
            'score'          => $score,
            'grade'          => $grade,
            'completedTasks' => $completedTasks,
            'pendingTasks'   => $pendingTasks,
            'overdueTasks'   => $overdueTasks,
            'workLogs'       => $workLogs,
            'totalHours'     => $totalHours,
            'daysPresent'    => $daysPresent,
            'completionRate' => $completionRate,
            'onTimeRate'     => $onTimeRate,
            'summaryPoints'  => $summaryPoints,
        ];
    }
}
