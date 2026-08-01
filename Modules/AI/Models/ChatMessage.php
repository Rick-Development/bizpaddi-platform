<?php

namespace Modules\AI\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'conversation_id',
        'role',
        'message',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the conversation that owns the message.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatConversation::class, 'conversation_id');
    }

    /**
     * Scope for user messages.
     */
    public function scopeUser($query)
    {
        return $query->where('role', 'user');
    }

    /**
     * Scope for assistant messages.
     */
    public function scopeAssistant($query)
    {
        return $query->where('role', 'assistant');
    }
}
