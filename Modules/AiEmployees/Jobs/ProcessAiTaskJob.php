<?php

namespace Modules\AiEmployees\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Modules\AiEmployees\Models\AiTask;
use Modules\AiEmployees\Models\AiEmployee;
use Modules\AiEmployees\Services\AiOrchestratorService;
use Exception;

class ProcessAiTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // Allow up to 5 minutes for AI processing

    protected $aiTask;

    /**
     * Create a new job instance.
     */
    public function __construct(AiTask $aiTask) 
    {
        $this->aiTask = $aiTask;
    }

    /**
     * Execute the job.
     */
    public function handle(AiOrchestratorService $orchestrator): void 
    {
        $this->aiTask->update(['status' => 'processing']);

        try {
            $employee = AiEmployee::find($this->aiTask->employee_id);
            if (!$employee) {
                throw new Exception("Employee not found.");
            }

            $result = $orchestrator->processTask($employee, $this->aiTask->task);

            $this->aiTask->update([
                'status' => 'completed',
                'output' => json_encode($result)
            ]);

        } catch (Exception $e) {
            $this->aiTask->update([
                'status' => 'failed',
                'output' => json_encode(['text' => 'Error: ' . $e->getMessage()])
            ]);
        }
    }
}
