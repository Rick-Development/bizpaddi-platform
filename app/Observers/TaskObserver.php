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
        if ($task->assignee && $task->assignee->status === 'active' && $task->assignee->id !== auth()->id()) {
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
        if ($task->wasChanged('assigned_to') && $task->assignee && $task->assignee->status === 'active' && $task->assignee->id !== auth()->id()) {
            $task->assignee->notify(new TaskAssignedNotification($task));
        }

        // Check if status changed to 'review'
        if ($task->wasChanged('status') && $task->status === 'review') {
            // Notify active Project Managers
            $pms = \App\Models\User::where('status', 'active')->whereHas('roles', function($q){
                $q->where('name', 'project_manager');
            })->get();
            
            foreach($pms as $pm) {
                $pm->notify(new TaskReviewNotification($task));
            }
        }

        // Check if status changed to 'blocked'
        if ($task->wasChanged('status') && $task->status === 'blocked') {
            // Notify Assignee
            if ($task->assignee && $task->assignee->status === 'active') {
                $task->assignee->notify(new \App\Notifications\OperationalMailNotification('Task Blocked Alert', [
                    'task_title' => $task->title,
                    'name' => $task->assignee->name,
                    'reason' => 'Task status changed to blocked.',
                    'task_url' => route('team.tasks.show', $task->id),
                ]));
            }

            // Notify Project Manager
            if ($task->project && $task->project->manager && $task->project->manager->status === 'active') {
                $task->project->manager->notify(new \App\Notifications\OperationalMailNotification('Employee Blocker Alert', [
                    'task_title' => $task->title,
                    'employee_name' => $task->assignee->name ?? 'Team Member',
                    'reason' => 'Task marked as Blocked.',
                    'task_url' => route('admin.tasks.edit', $task->id),
                ]));

                $task->project->manager->notify(new \App\Notifications\OperationalMailNotification('Project Blocker Alert', [
                    'project_name' => $task->project->name,
                    'blocker_details' => 'Task "' . $task->title . '" is blocked.',
                    'project_url' => route('admin.pm-projects.show', $task->project->id),
                ]));
            }
        }

        // Check if completed task was reopened
        if ($task->wasChanged('status') && $task->getOriginal('status') === 'done' && in_array($task->status, ['todo', 'in_progress'])) {
            if ($task->project && $task->project->manager && $task->project->manager->status === 'active') {
                $task->project->manager->notify(new \App\Notifications\OperationalMailNotification('Task Reopened — PM Alert', [
                    'task_title' => $task->title,
                    'employee_name' => $task->assignee->name ?? 'Team Member',
                    'task_url' => route('admin.tasks.edit', $task->id),
                ]));
            }
        }
    }
}
