<?php

namespace Modules\AiEmployees\Console;

use Illuminate\Console\Command;
use Modules\AiEmployees\Models\AiEmployee;
use Modules\AiEmployees\Models\AiTask;
use Modules\AiEmployees\Jobs\ProcessAiTaskJob;

class RunAutonomousRoutinesCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'ai:run-autonomous-routines {--role= : Run routines only for employees of this specific role}';

    /**
     * The console command description.
     */
    protected $description = 'Trigger autonomous periodic execution routines for active AI Employees';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = AiEmployee::where('is_active', true)->whereNull('parent_id');

        if ($role = $this->option('role')) {
            $query->where('role', $role);
        }

        $activeEmployees = $query->get();

        if ($activeEmployees->isEmpty()) {
            $this->info('No active primary AI Employees found.');
            return;
        }

        $this->info("Found {$activeEmployees->count()} active primary AI Employees. Triggering routines...");

        // Predefined periodic routine prompts for each role
        // Only General Manager and Operations Manager execute autonomous routine checks.
        // Other agents (SMM, BDM, PM, Finance) remain in standby until delegated to by managers or humans.
        $routines = [
            'general_manager' => "As the General Manager, run your high-level business review: check overall operational statistics, review active projects and revenue, and outline strategy priorities.",
            
            'operations_manager' => "As the Operations Manager, run your system performance review: check server statistics, monitor team logs, and flag operations bottlenecks."
        ];

        foreach ($activeEmployees as $employee) {
            $routinePrompt = $routines[$employee->role] ?? null;

            if ($routinePrompt) {
                $this->line("Queueing routine task for: {$employee->name} ({$employee->role})");

                // Create a task representing the periodic check
                $task = AiTask::create([
                    'employee_id' => $employee->id,
                    'task' => $routinePrompt,
                    'status' => 'pending'
                ]);

                // Dispatch the processing job
                ProcessAiTaskJob::dispatch($task);
            } else {
                $this->warn("No periodic routine defined for role: {$employee->role} (Employee: {$employee->name})");
            }
        }

        $this->info('All active routines successfully queued.');
    }
}
