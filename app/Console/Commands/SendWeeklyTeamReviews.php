<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Modules\ProjectManagement\Services\PerformanceEngine;
use App\Mail\WeeklyTeamReviewMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendWeeklyTeamReviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'team:send-weekly-reviews 
                            {--user= : Send review for a specific user ID}
                            {--week= : Reference date for the week (e.g. 2026-09-28, default: past completed week)}
                            {--dry-run : Output stats without actually sending emails}
                            {--force : Send to users regardless of active status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly performance review summaries to team members via email';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting automated weekly team performance reviews dispatch...');

        // 1. Determine evaluation week
        if ($this->option('week')) {
            $refDate = Carbon::parse($this->option('week'));
            $startDate = $refDate->copy()->startOfWeek();
            $endDate = $refDate->copy()->endOfWeek();
        } else {
            // Default to the previous fully-completed week (Monday to Sunday)
            $startDate = Carbon::now()->subWeek()->startOfWeek();
            $endDate = Carbon::now()->subWeek()->endOfWeek();
        }

        $periodLabel = $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y');
        $this->info("Evaluation Period: {$periodLabel}");

        // 2. Query target team members
        $query = User::query();

        if (!$this->option('force')) {
            $query->where('status', 'active');
        }

        if ($this->option('user')) {
            $query->where('id', $this->option('user'));
        }

        $users = $query->orderBy('name')->get();

        if ($users->isEmpty()) {
            $this->warn('No eligible team members found for evaluation.');
            return Command::SUCCESS;
        }

        $isDryRun = (bool) $this->option('dry-run');
        if ($isDryRun) {
            $this->warn('Running in DRY-RUN mode. No actual emails will be dispatched.');
        }

        $totalSent = 0;
        $totalErrors = 0;
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            try {
                // Calculate member assessment for the week
                $assessment = PerformanceEngine::getMemberAssessment($user->id, $startDate, $endDate);

                if ($isDryRun) {
                    $this->newLine();
                    $this->info("-> [DRY RUN] {$user->name} ({$user->email}) | Score: {$assessment['score']}% | Completed: " . count($assessment['completedTasks']) . " | Pending: " . count($assessment['pendingTasks']) . " | Overdue: " . count($assessment['overdueTasks']));
                    $totalSent++;
                } else {
                    Mail::to($user->email)->send(new WeeklyTeamReviewMail($user, $assessment));
                    $totalSent++;
                    $this->newLine();
                    $this->info("✓ Dispatched weekly review to {$user->name} <{$user->email}>");
                }
            } catch (\Throwable $e) {
                $totalErrors++;
                $this->newLine();
                $this->error("✗ Failed to send weekly review to {$user->name}: {$e->getMessage()}");
                Log::error("Failed to send weekly review to User ID {$user->id} ({$user->email}): " . $e->getMessage(), [
                    'exception' => $e
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Completed weekly team reviews dispatch!");
        $this->info("Total processed: {$totalSent}");
        if ($totalErrors > 0) {
            $this->warn("Total errors: {$totalErrors}");
        }

        return Command::SUCCESS;
    }
}
