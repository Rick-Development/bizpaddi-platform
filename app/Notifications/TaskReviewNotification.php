<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Channels\FcmChannel;
use Modules\ProjectManagement\Models\Task;

class TaskReviewNotification extends Notification
{
    use Queueable;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['database', FcmChannel::class, 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Task Ready for Review',
            'message' => 'The task "' . $this->task->title . '" is ready for review.',
            'url' => route('admin.tasks.edit', $this->task->id), // PMs/Admins usually review
            'icon' => 'eye',
            'type' => 'task_review',
            'task_id' => $this->task->id
        ];
    }

    public function toFcm($notifiable)
    {
        return [
            'title' => 'Task Ready for Review',
            'body' => 'The task "' . $this->task->title . '" is ready for review.',
            'data' => [
                'url' => route('admin.tasks.edit', $this->task->id),
                'type' => 'task_review'
            ]
        ];
    }

    public function toMail($notifiable)
    {
        return (new \App\Notifications\OperationalMailNotification('Task Submitted for Review', [
            'task_title' => $this->task->title,
            'employee_name' => $this->task->assignee->name ?? 'Team Member',
            'task_url' => route('admin.tasks.edit', $this->task->id),
        ]))->toMail($notifiable);
    }
}
