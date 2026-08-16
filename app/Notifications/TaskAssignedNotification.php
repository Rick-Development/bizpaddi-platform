<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Channels\FcmChannel;
use Modules\ProjectManagement\Models\Task;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database', FcmChannel::class];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Task Assigned',
            'message' => 'You have been assigned to: ' . $this->task->title,
            'url' => route('team.tasks.show', $this->task->id),
            'icon' => 'clipboard-document-check',
            'type' => 'task_assigned',
            'task_id' => $this->task->id
        ];
    }

    public function toFcm($notifiable)
    {
        return [
            'title' => 'New Task Assigned',
            'body' => 'You have been assigned to: ' . $this->task->title,
            'data' => [
                'url' => route('team.tasks.show', $this->task->id),
                'type' => 'task_assigned'
            ]
        ];
    }
}
