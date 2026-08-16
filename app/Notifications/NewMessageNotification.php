<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\FcmChannel;
use Modules\ProjectManagement\Models\ProjectMessage;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;

    public function __construct(ProjectMessage $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database', 'mail', FcmChannel::class];
    }

    public function toMail($notifiable)
    {
        $url = route('team.projects.show', $this->message->conversation->project_id ?? '');

        return (new MailMessage)
                    ->subject('New Message in Project: ' . ($this->message->conversation->project->name ?? 'Chat'))
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('You have received a new message from ' . ($this->message->sender->name ?? 'someone') . '.')
                    ->line('"' . \Illuminate\Support\Str::limit(strip_tags($this->message->content), 100) . '"')
                    ->action('View Message', $url)
                    ->line('Thank you for using ' . config('app.name') . '!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'new_message',
            'message_id' => $this->message->id,
            'title' => 'New Message',
            'body' => ($this->message->sender->name ?? 'Someone') . ' sent a message: ' . \Illuminate\Support\Str::limit(strip_tags($this->message->content), 50),
            'url' => route('team.projects.show', $this->message->conversation->project_id ?? ''),
        ];
    }

    public function toFcm($notifiable)
    {
        return [
            'title' => 'New Message',
            'body' => ($this->message->sender->name ?? 'Someone') . ' sent a message.',
            'url' => route('team.projects.show', $this->message->conversation->project_id ?? ''),
        ];
    }
}
