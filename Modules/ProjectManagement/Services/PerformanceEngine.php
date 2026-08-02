<?php

namespace Modules\ProjectManagement\Services;

use Modules\ProjectManagement\Models\Task;
use Modules\TeamPortal\Models\WorkLog;
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
}
