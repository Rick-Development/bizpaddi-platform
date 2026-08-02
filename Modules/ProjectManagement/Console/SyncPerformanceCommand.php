<?php

namespace Modules\ProjectManagement\Console;

use Illuminate\Console\Command;
use Modules\TeamPortal\Models\WorkLog;
use Modules\ProjectManagement\Models\Performance;
use Modules\ProjectManagement\Services\PerformanceEngine;
use App\Models\User;
use Carbon\Carbon;

class SyncPerformanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'performance:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force checkout overtime worklogs and sync team performance for the current month.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting Performance Sync...');
        
        $now = Carbon::now();

        // 1. Force Checkout Overtime Sessions
        $activeLogs = WorkLog::where('status', 'in_progress')->get();
        $forceClosedCount = 0;

        foreach ($activeLogs as $log) {
            $runningMinutes = Carbon::parse($log->start_time)->diffInMinutes($now);
            if ($runningMinutes > (14 * 60)) {
                $log->update([
                    'end_time' => $now,
                    'duration_minutes' => $runningMinutes,
                    'status' => 'completed',
                    'notes' => trim($log->notes . "\n[System: Auto-checkout due to timeout]")
                ]);
                $forceClosedCount++;
            }
        }
        
        if ($forceClosedCount > 0) {
            $this->info("Force-closed {$forceClosedCount} overtime work sessions.");
        }

        // 2. Sync Monthly Performance Data for all users
        $users = User::all();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        foreach ($users as $user) {
            // Calculate using our dynamic engine for this month
            $metrics = PerformanceEngine::calculate($user->id, $startOfMonth, $endOfMonth);
            
            // Sync to the database table for legacy compatibility and tracking
            Performance::updateOrCreate(
                ['user_id' => $user->id, 'month' => $now->month, 'year' => $now->year],
                [
                    'score' => $metrics['score'],
                    'tasks_completed' => $metrics['tasks']['completed'],
                    'tasks_late' => $metrics['tasks']['late'],
                    'tasks_early' => $metrics['tasks']['early'],
                    'bonus_points' => $metrics['total_bonus'],
                    'penalty_points' => $metrics['total_penalty'],
                    'kpi_data' => $metrics // Store full breakdown in JSON
                ]
            );
        }

        $this->info('Performance successfully synced for ' . $users->count() . ' users.');
        return Command::SUCCESS;
    }
}
