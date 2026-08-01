<?php

namespace Modules\ClientPortal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ChatMessage extends Model
{
    protected $table = 'client_chat_messages';

    protected $fillable = [
        'client_chat_id',
        'sender_id',
        'sender_type',
        'message',
        'attachment_path',
        'attachment_type',
        'attachment_name',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    protected $appends = ['attachment_url', 'is_from_client'];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'client_chat_id');
    }

    public function sender()
    {
        return $this->morphTo();
    }

    public function getAttachmentUrlAttribute()
    {
        if (!$this->attachment_path) {
            return null;
        }

        return Storage::url($this->attachment_path);
    }

    public function getIsFromClientAttribute()
    {
        return $this->sender_type === 'user';
    }

    public function markAsRead()
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }
}
