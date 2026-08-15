<?php

namespace Modules\ProjectManagement\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\ProjectManagement\Models\ProjectMessage;

class MessageSent implements ShouldBroadcast
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
        return [
            new PrivateChannel('conversation.' . $this->message->conversation_id),
        ];
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

        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'content' => $this->message->content,
            'sender_id' => $this->message->sender_id,
            'sender_type' => $this->message->sender_type,
            'sender_name' => $sender ? $sender->name : 'Unknown User',
            'sender_avatar' => $avatarUrl,
            'created_at' => $this->message->created_at->toIso8601String(),
            'formatted_time' => $this->message->created_at->format('g:i A'),
        ];
    }
}
