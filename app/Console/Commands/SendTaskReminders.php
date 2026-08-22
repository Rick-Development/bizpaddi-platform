<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\ProjectManagement\Models\Task;
use App\Notifications\TaskDueReminderNotification;
use Carbon\Carbon;

class SendTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for tasks that are due soon (within 24 hours) or overdue.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $today = Carbon::today();
        $tomorrow = Carbon::now()->addDay();
        $in48Hours = Carbon::now()->addHours(48);
        $in3Days = Carbon::now()->addDays(3);

        // Fetch active incomplete tasks
        $activeTasks = Task::whereIn('status', ['todo', 'in_progress', 'review', 'testing'])->get();

        // 1. Task Due Soon (48 Hours) to Assignee
        foreach ($activeTasks as $task) {
            if ($task->due_date && $task->due_date->between($now, $in48Hours)) {
                if ($task->assignee && $task->assignee->status === 'active') {
                    $task->assignee->notify(new \App\Notifications\OperationalMailNotification('Task Due Soon', [
                        'assignee_name' => $task->assignee->name,
                        'task_title' => $task->title,
                        'task_due_date' => $task->due_date->format('Y-m-d H:i'),
                        'task_url' => route('team.tasks.show', $task->id),
                    ]));
                }
            }
        }

        // 2. Task Due Soon — PM Alert (24 Hours)
        foreach ($activeTasks as $task) {
            if ($task->due_date && $task->due_date->between($now, $tomorrow)) {
                $pm = $task->project->manager ?? null;
                if ($pm && $pm->status === 'active') {
                    $pm->notify(new \App\Notifications\OperationalMailNotification('Task Due Soon — PM Alert', [
                        'tasks_list' => "<li><strong>{$task->title}</strong> (Assigned to: " . ($task->assignee->name ?? 'None') . ") due at " . $task->due_date->format('H:i') . "</li>",
                    ]));
                }
            }
        }

        // 3. Task Due Today to Assignee & PM Alert
        foreach ($activeTasks as $task) {
            if ($task->due_date && $task->due_date->isToday()) {
                if ($task->assignee && $task->assignee->status === 'active') {
                    $task->assignee->notify(new \App\Notifications\OperationalMailNotification('Task Due Today', [
                        'assignee_name' => $task->assignee->name,
                        'task_title' => $task->title,
                        'task_due_date' => $task->due_date->format('Y-m-d H:i'),
                        'task_url' => route('team.tasks.show', $task->id),
                    ]));
                }
                $pm = $task->project->manager ?? null;
                if ($pm && $pm->status === 'active') {
                    $pm->notify(new \App\Notifications\OperationalMailNotification('Task Due Today — PM Alert', [
                        'tasks_list' => "<li><strong>{$task->title}</strong> (Assigned to: " . ($task->assignee->name ?? 'None') . ")</li>",
                    ]));
                }
            }
        }

        // 4. Overdue Tasks & PM Escalations
        foreach ($activeTasks as $task) {
            if ($task->due_date && $task->due_date->isPast()) {
                // Immediate assignee notification
                if ($task->assignee && $task->assignee->status === 'active') {
                    $task->assignee->notify(new \App\Notifications\OperationalMailNotification('Task Overdue', [
                        'assignee_name' => $task->assignee->name,
                        'task_title' => $task->title,
                        'task_due_date' => $task->due_date->format('Y-m-d'),
                        'task_url' => route('team.tasks.show', $task->id),
                    ]));
                }

                $pm = $task->project->manager ?? null;
                if ($pm && $pm->status === 'active') {
                    // Send general overdue notification to PM
                    $pm->notify(new \App\Notifications\OperationalMailNotification('Task Overdue — PM Alert', [
                        'task_title' => $task->title,
                        'assignee_name' => $task->assignee->name ?? 'None',
                        'task_due_date' => $task->due_date->format('Y-m-d'),
                        'task_url' => route('admin.tasks.edit', $task->id),
                    ]));

                    // Check if critical overdue (> 48 hours past due)
                    if ($task->due_date->diffInHours($now) > 48) {
                        $pm->notify(new \App\Notifications\OperationalMailNotification('Critical Task Overdue — PM Escalation', [
                            'task_title' => $task->title,
                            'assignee_name' => $task->assignee->name ?? 'None',
                            'task_due_date' => $task->due_date->format('Y-m-d'),
                            'task_url' => route('admin.tasks.edit', $task->id),
                        ]));
                    }
                }
            }
        }

        // 5. Task Not Started (3 Days untouched in todo)
        foreach ($activeTasks as $task) {
            if ($task->status === 'todo' && $task->created_at->diffInDays($now) >= 3) {
                $pm = $task->project->manager ?? null;
                if ($pm && $pm->status === 'active') {
                    $pm->notify(new \App\Notifications\OperationalMailNotification('Task Not Started — PM Alert', [
                        'task_title' => $task->title,
                        'assignee_name' => $task->assignee->name ?? 'None',
                        'task_url' => route('admin.tasks.edit', $task->id),
                    ]));
                }
            }
        }

        // 6. Task Inactivity (In progress > 3 days without updates/logs)
        foreach ($activeTasks as $task) {
            $lastUpdate = $task->status_changed_at ?: $task->updated_at;
            if ($task->status === 'in_progress' && $lastUpdate->diffInDays($now) >= 3) {
                if ($task->assignee && $task->assignee->status === 'active') {
                    $task->assignee->notify(new \App\Notifications\OperationalMailNotification('Task Inactivity Alert', [
                        'assignee_name' => $task->assignee->name,
                        'task_title' => $task->title,
                        'task_url' => route('team.tasks.show', $task->id),
                    ]));
                }

                $pm = $task->project->manager ?? null;
                if ($pm && $pm->status === 'active') {
                    $pm->notify(new \App\Notifications\OperationalMailNotification('Task Inactivity — PM Alert', [
                        'task_title' => $task->title,
                        'assignee_name' => $task->assignee->name ?? 'None',
                        'task_url' => route('admin.tasks.edit', $task->id),
                    ]));
                }
            }
        }

        // 7. Review Pending Reminder (In review > 24 hours)
        foreach ($activeTasks as $task) {
            $lastUpdate = $task->status_changed_at ?: $task->updated_at;
            if (in_array($task->status, ['review', 'testing']) && $lastUpdate->diffInHours($now) >= 24) {
                $pm = $task->project->manager ?? null;
                if ($pm && $pm->status === 'active') {
                    $pm->notify(new \App\Notifications\OperationalMailNotification('Review Pending Reminder', [
                        'reviews_url' => route('admin.kanban.index'),
                    ]));
                }
            }
        }

        // 8. Milestone Reminders
        $milestones = \Modules\ProjectManagement\Models\Milestone::all();
        foreach ($milestones as $milestone) {
            $isCompleted = $milestone->progress >= 100 || $milestone->status === 'completed';
            $pm = $milestone->project->manager ?? null;

            if ($pm && $pm->status === 'active' && !$isCompleted && $milestone->due_date) {
                // Milestone approaching (due within 3 days)
                $milestoneDueDate = Carbon::parse($milestone->due_date);
                if ($milestoneDueDate->between($now, $in3Days)) {
                    $pm->notify(new \App\Notifications\OperationalMailNotification('Milestone Approaching', [
                        'milestone_title' => $milestone->title,
                        'project_name' => $milestone->project->name ?? 'N/A',
                        'due_date' => $milestoneDueDate->format('Y-m-d'),
                        'project_url' => route('admin.pm-projects.show', $milestone->project_id),
                    ]));
                }

                // Milestone missed
                if ($milestoneDueDate->isPast()) {
                    $pm->notify(new \App\Notifications\OperationalMailNotification('Milestone Missed', [
                        'milestone_title' => $milestone->title,
                        'project_name' => $milestone->project->name ?? 'N/A',
                        'due_date' => $milestoneDueDate->format('Y-m-d'),
                        'project_url' => route('admin.pm-projects.show', $milestone->project_id),
                    ]));
                }
            }
        }

        $this->info('Task and milestone reminders checked & dispatched successfully.');
        return Command::SUCCESS;
    }
}
