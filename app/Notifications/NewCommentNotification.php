<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\FcmChannel;
use Modules\ProjectManagement\Models\Comment;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['database', 'mail', FcmChannel::class];
    }

    public function toMail($notifiable)
    {
        $type = class_basename($this->comment->commentable_type);
        
        // Provide a generic fallback URL if the specific route isn't available
        $url = url('/');

        return (new MailMessage)
                    ->subject("New Comment on $type")
                    ->greeting('Hello ' . $notifiable->name . '!')
                    ->line('Someone left a new comment on a ' . $type . ' you are involved with.')
                    ->line('"' . \Illuminate\Support\Str::limit(strip_tags($this->comment->content), 100) . '"')
                    ->action('View details', $url)
                    ->line('Thank you for using ' . config('app.name') . '!');
    }

    public function toDatabase($notifiable)
    {
        $type = class_basename($this->comment->commentable_type);
        return [
            'type' => 'new_comment',
            'comment_id' => $this->comment->id,
            'title' => 'New Comment',
            'body' => 'New comment on ' . $type . ': ' . \Illuminate\Support\Str::limit(strip_tags($this->comment->content), 50),
            'url' => url('/'),
        ];
    }

    public function toFcm($notifiable)
    {
        $type = class_basename($this->comment->commentable_type);
        return [
            'title' => 'New Comment',
            'body' => 'Someone commented on a ' . $type . '.',
            'url' => url('/'),
        ];
    }
}
