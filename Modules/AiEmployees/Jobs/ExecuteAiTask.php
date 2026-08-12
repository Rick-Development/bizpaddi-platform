<?php

namespace Modules\AiEmployees\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\AiEmployees\Models\AiEmployee;
use Modules\AiEmployees\Services\AiOrchestratorService;

class ExecuteAiTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $employee;
    protected $taskText;
    protected $context;

    /**
     * Create a new job instance.
     */
    public function __construct(AiEmployee $employee, string $taskText, array $context = [])
    {
        $this->employee = $employee;
        $this->taskText = $taskText;
        $this->context = $context;
    }

    /**
     * Execute the job.
     */
    public function handle(AiOrchestratorService $orchestrator)
    {
        // Don't execute if the AI employee is deactivated
        if (!$this->employee->is_active) {
            \Log::info("Skipping task for AI Employee {$this->employee->name} because they are inactive.");
            return;
        }

        \Log::info("AI Employee {$this->employee->name} is starting task: {$this->taskText}");

        $result = $orchestrator->processTask($this->employee, $this->taskText, $this->context);

        if ($result['success']) {
            \Log::info("AI Employee {$this->employee->name} completed task. Result: " . substr($result['result'], 0, 100) . '...');
            
            // In a fully built-out architecture, this would write to a task_logs table or notify the Command Center
        } else {
            \Log::error("AI Employee {$this->employee->name} failed task. Error: " . $result['error']);
        }
    }
}
