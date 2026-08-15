<?php

namespace Modules\ProjectManagement\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversationId;
    public $readByUserId;
    public $readByUserType;

    /**
     * Create a new event instance.
     */
    public function __construct($conversationId, $readByUserId, $readByUserType)
    {
        $this->conversationId = $conversationId;
        $this->readByUserId = $readByUserId;
        $this->readByUserType = $readByUserType;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'MessageRead';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->conversationId),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'read_by_user_id' => $this->readByUserId,
            'read_by_user_type' => $this->readByUserType,
            'read_at' => now()->toIso8601String(),
        ];
    }
}
