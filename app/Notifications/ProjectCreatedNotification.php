<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Channels\FcmChannel;
use Modules\ProjectManagement\Models\Project;

class ProjectCreatedNotification extends Notification
{
    use Queueable;

    public $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function via($notifiable)
    {
        return ['database', FcmChannel::class];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Project Created',
            'message' => 'The project "' . $this->project->name . '" has been created.',
            'url' => route('team.projects.show', $this->project->id),
            'icon' => 'folder-plus',
            'type' => 'project_created',
            'project_id' => $this->project->id
        ];
    }

    public function toFcm($notifiable)
    {
        return [
            'title' => 'New Project Created',
            'body' => 'The project "' . $this->project->name . '" has been created.',
            'data' => [
                'url' => route('team.projects.show', $this->project->id),
                'type' => 'project_created'
            ]
        ];
    }
}
