<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\FcmChannel;
use Modules\ClientPortal\Models\ChatMessage;

class ClientChatMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;

    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database', 'mail', FcmChannel::class];
    }

    public function toMail($notifiable)
    {
        $url = route('client.chat.index'); // Assuming this is the chat route

        return (new MailMessage)
                    ->subject('New Direct Message')
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('You have received a new direct message.')
                    ->line('"' . \Illuminate\Support\Str::limit(strip_tags($this->message->message), 100) . '"')
                    ->action('View Message', $url)
                    ->line('Thank you for using ' . config('app.name') . '!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'new_direct_message',
            'message_id' => $this->message->id,
            'title' => 'New Direct Message',
            'body' => 'You received a new message: ' . \Illuminate\Support\Str::limit(strip_tags($this->message->message), 50),
            'url' => route('client.chat.index'),
        ];
    }

    public function toFcm($notifiable)
    {
        return [
            'title' => 'New Direct Message',
            'body' => 'You received a new message.',
            'url' => route('client.chat.index'),
        ];
    }
}
