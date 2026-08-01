<?php

namespace Modules\ProjectManagement\Services;

use Modules\ProjectManagement\Models\Task;
use Modules\ProjectManagement\Models\Performance;
use Carbon\Carbon;

class PerformanceService
{
    /**
     * Update/Calculate performance for a user for the current month.
     */
    public function updateMonthlyPerformance($userId)
    {
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;

        $performance = Performance::firstOrCreate(
            ['user_id' => $userId, 'month' => $month, 'year' => $year],
            ['score' => 100.00]
        );

        // 1. Completed Tasks (Bonuses and existing penalties)
        $tasks = Task::where('assigned_to', $userId)
            ->whereYear('completed_at', $year)
            ->whereMonth('completed_at', $month)
            ->where('status', 'done')
            ->get();

        $completedCount = $tasks->count();
        $lateCount = 0;
        $earlyCount = 0;
        $penaltyPoints = 0;
        $bonusPoints = 0;

        foreach ($tasks as $task) {
            if ($task->due_date) {
                if ($task->completed_at > $task->due_date) {
                    $lateCount++;
                    $penaltyPoints += 5.00; // Deduct 5 points per late task
                } elseif ($task->completed_at->diffInDays($task->due_date) >= 1) {
                    $earlyCount++;
                    $bonusPoints += 2.00; // Add 2 bonus points per early task
                }
            }
        }

        // 2. Currently Late Tasks (Ongoing Penalities)
        // If a task is past due but not DONE, it should also count as late
        $currentlyLate = Task::where('assigned_to', $userId)
            ->where('status', '!=', 'done')
            ->where('due_date', '<', Carbon::now())
            ->whereMonth('due_date', $month)
            ->whereYear('due_date', $year)
            ->count();

        $lateCount += $currentlyLate;
        $penaltyPoints += ($currentlyLate * 5.00);

        // Base score is 100. Apply bonuses and penalties.
        $finalScore = 100.00 - $penaltyPoints + $bonusPoints;

        // Keep score between 0 and 150 (for extreme high performers)
        $finalScore = max(0, min(150, $finalScore));

        $performance->update([
            'tasks_completed' => $completedCount,
            'tasks_late' => $lateCount,
            'tasks_early' => $earlyCount,
            'penalty_points' => $penaltyPoints,
            'bonus_points' => $bonusPoints,
            'score' => $finalScore,
        ]);

        return $performance;
    }
}
