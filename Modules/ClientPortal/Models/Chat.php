<?php

namespace Modules\ClientPortal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Chat extends Model
{
    protected $table = 'client_chats';

    protected $fillable = [
        'user_id',
        'admin_id',
        'subject',
        'is_closed',
    ];

    protected $casts = [
        'is_closed' => 'boolean',
    ];

    protected $appends = ['unread_count', 'last_message'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'client_chat_id')->orderBy('created_at', 'asc');
    }

    public function getUnreadCountAttribute()
    {
        return $this->messages()
            ->where('sender_type', '!=', 'user')
            ->whereNull('read_at')
            ->count();
    }

    public function getLastMessageAttribute()
    {
        $latest = $this->messages()->orderBy('created_at', 'desc')->first();
        if (!$latest) return null;
        
        return [
            'message' => $latest->message,
            'created_at' => $latest->created_at,
            'has_attachment' => !empty($latest->attachment_path),
        ];
    }
}
