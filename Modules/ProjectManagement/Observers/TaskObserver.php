<?php

namespace Modules\ProjectManagement\Observers;

use Modules\ProjectManagement\Models\Task;
use Modules\ProjectManagement\Services\PerformanceService;
use Carbon\Carbon;

class TaskObserver
{
    /**
     * Handle the Task "updating" event.
     */
    public function updating(Task $task)
    {
        // If status changes to 'done' and completed_at is not set
        if ($task->isDirty('status') && $task->status === 'done' && is_null($task->completed_at)) {
            $task->completed_at = Carbon::now();
        }

        // If status changes FROM 'done' back to something else, clear completed_at
        if ($task->isDirty('status') && $task->status !== 'done') {
            $task->completed_at = null;
        }
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task)
    {
        // If status was changed, update performance tracking
        if ($task->wasChanged('status') || $task->wasChanged('completed_at')) {
            if ($task->assigned_to) {
                (new PerformanceService())->updateMonthlyPerformance($task->assigned_to);
            }
        }
    }
}
