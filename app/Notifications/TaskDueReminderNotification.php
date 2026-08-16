<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Channels\FcmChannel;
use Modules\ProjectManagement\Models\Task;

use Illuminate\Notifications\Messages\MailMessage;

class TaskDueReminderNotification extends Notification
{
    use Queueable;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database', 'mail', FcmChannel::class]; 
    }

    public function toMail($notifiable)
    {
        $url = route('team.tasks.show', $this->task->id);

        return (new MailMessage)
                    ->subject('Task Due Reminder: ' . $this->task->title)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('This is a gentle reminder that your task is due ' . $this->task->due_date->diffForHumans() . '.')
                    ->line('Task: ' . $this->task->title)
                    ->line('Due Date: ' . $this->task->due_date->format('M d, Y h:i A'))
                    ->action('View Task', $url)
                    ->line('Thank you for using ' . config('app.name') . '!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Task Due Soon',
            'message' => 'Reminder: The task "' . $this->task->title . '" is due ' . $this->task->due_date->diffForHumans(),
            'url' => route('team.tasks.show', $this->task->id),
            'icon' => 'clock',
            'type' => 'task_due',
            'task_id' => $this->task->id
        ];
    }

    public function toFcm($notifiable)
    {
        return [
            'title' => 'Task Due Soon',
            'body' => 'Reminder: The task "' . $this->task->title . '" is due ' . $this->task->due_date->diffForHumans(),
            'data' => [
                'url' => route('team.tasks.show', $this->task->id),
                'type' => 'task_due'
            ]
        ];
    }
}
