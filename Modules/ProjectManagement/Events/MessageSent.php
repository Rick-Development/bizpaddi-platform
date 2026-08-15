<?php

namespace Modules\ProjectManagement\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\ProjectManagement\Models\ProjectMessage;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(ProjectMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('conversation.' . $this->message->conversation_id),
        ];

        // Broadcast to all participants in this conversation so their personal global listener receives it
        if ($this->message->conversation) {
            foreach ($this->message->conversation->participants as $participant) {
                if ((int)$participant->user_id !== (int)$this->message->sender_id || $participant->user_type !== $this->message->sender_type) {
                    $prefix = str_contains($participant->user_type, 'Admin') ? 'admin' : 'user';
                    $channels[] = new PrivateChannel($prefix . '.messages.' . $participant->user_id);
                }
            }
        }

        return $channels;
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $sender = $this->message->sender;
        
        // Resolve sender avatar url safely
        $avatarUrl = null;
        if ($sender) {
            if (method_exists($sender, 'getFirstMediaUrl') && $sender->getFirstMediaUrl('avatar')) {
                $avatarUrl = $sender->getFirstMediaUrl('avatar');
            }
        }

        $isRead = $this->message->reads()
            ->where(function($q) {
                $q->where('user_id', '!=', $this->message->sender_id)
                  ->orWhere('user_type', '!=', $this->message->sender_type);
            })->exists() || $this->message->read_at !== null;

        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'content' => $this->message->content,
            'sender_id' => $this->message->sender_id,
            'sender_type' => $this->message->sender_type,
            'sender_name' => $sender ? $sender->name : 'Unknown User',
            'sender_avatar' => $avatarUrl,
            'is_read' => $isRead,
            'created_at' => $this->message->created_at->toIso8601String(),
            'formatted_time' => $this->message->created_at->format('g:i A'),
        ];
    }
}
