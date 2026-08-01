<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ChatMessageRead extends Model
{
    protected $table = 'project_message_reads';

    protected $fillable = [
        'message_id',
        'user_id',
        'user_type',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(ProjectMessage::class, 'message_id');
    }

    public function user(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'user_type', 'user_id');
    }
}
