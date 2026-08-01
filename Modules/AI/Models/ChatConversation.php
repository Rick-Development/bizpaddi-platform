<?php

namespace Modules\AI\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class ChatConversation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'session_id',
        'visitor_name',
        'visitor_email',
        'status',
        'sentiment',
        'summary',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    /**
     * Get the messages for the conversation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id');
    }

    /**
     * Get the user that owns the conversation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for active conversations.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for resolved conversations.
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }
}
