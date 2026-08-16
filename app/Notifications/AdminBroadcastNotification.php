<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\FcmChannel;

class AdminBroadcastNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $title;
    public $message;

    public function __construct($title, $message)
    {
        $this->title = $title;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database', 'mail', FcmChannel::class];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->title)
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line($this->message)
                    ->line('Thank you for using ' . config('app.name') . '!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'admin_broadcast',
            'title' => $this->title,
            'body' => $this->message,
            'url' => route('team.dashboard'),
        ];
    }

    public function toFcm($notifiable)
    {
        return [
            'title' => $this->title,
            'body' => $this->message,
            'url' => route('team.dashboard'),
        ];
    }
}
