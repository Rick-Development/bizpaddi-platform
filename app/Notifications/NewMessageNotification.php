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

    private function getChatUrl($notifiable)
    {
        $conversationId = $this->message->conversation_id;
        if (str_contains(get_class($notifiable), 'Admin')) {
            return route('admin.chat.index', ['conversation' => $conversationId]);
        }
        return route('team.chat.index', ['conversation' => $conversationId]);
    }

    public function toMail($notifiable)
    {
        $url = $this->getChatUrl($notifiable);
        $projectName = $this->message->conversation->project->name ?? 'Chat Room';

        return (new MailMessage)
                    ->subject('New Message in: ' . $projectName)
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
            'url' => $this->getChatUrl($notifiable),
        ];
    }

    public function toFcm($notifiable)
    {
        return [
            'title' => 'New Message',
            'body' => ($this->message->sender->name ?? 'Someone') . ' sent a message.',
            'url' => $this->getChatUrl($notifiable),
        ];
    }
}
