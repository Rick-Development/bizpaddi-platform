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
        $tomorrow = Carbon::now()->addDay();

        // Get tasks that are due between now and tomorrow, and are not completed
        $dueSoonTasks = Task::whereNotNull('due_date')
            ->whereIn('status', ['todo', 'in_progress'])
            ->whereBetween('due_date', [$now, $tomorrow])
            ->get();

        foreach ($dueSoonTasks as $task) {
            if ($task->assignee) {
                $task->assignee->notify(new TaskDueReminderNotification($task));
            }
        }

        $this->info(count($dueSoonTasks) . ' task reminders sent successfully.');
    }
}
