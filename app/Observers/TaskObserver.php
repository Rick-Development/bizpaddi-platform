<?php

namespace App\Observers;

use Modules\ProjectManagement\Models\Task;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskReviewNotification;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     *
     * @param  \Modules\ProjectManagement\Models\Task  $task
     * @return void
     */
    public function created(Task $task)
    {
        if ($task->assignee && $task->assignee->id !== auth()->id()) {
            $task->assignee->notify(new TaskAssignedNotification($task));
        }
    }

    /**
     * Handle the Task "updated" event.
     *
     * @param  \Modules\ProjectManagement\Models\Task  $task
     * @return void
     */
    public function updated(Task $task)
    {
        // Check if assignee changed
        if ($task->wasChanged('assigned_to') && $task->assignee && $task->assignee->id !== auth()->id()) {
            $task->assignee->notify(new TaskAssignedNotification($task));
        }

        // Check if status changed to 'review'
        if ($task->wasChanged('status') && $task->status === 'review') {
            // Notify Project Managers
            $pms = \App\Models\User::whereHas('roles', function($q){
                $q->where('name', 'project_manager');
            })->get();
            
            foreach($pms as $pm) {
                $pm->notify(new TaskReviewNotification($task));
            }
        }
    }
}
