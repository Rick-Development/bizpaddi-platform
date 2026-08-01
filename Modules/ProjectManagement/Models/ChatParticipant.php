<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ChatParticipant extends Model
{
    protected $table = 'project_participants';

    protected $fillable = [
        'conversation_id',
        'user_id',
        'user_type',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ProjectConversation::class, 'conversation_id');
    }

    public function user(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'user_type', 'user_id');
    }
}
