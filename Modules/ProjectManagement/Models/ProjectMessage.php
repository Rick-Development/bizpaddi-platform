<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProjectMessage extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'project_messages';

    protected $fillable = [
        'conversation_id',
        'parent_id',
        'sender_id',
        'sender_type',
        'content',
        'read_at',
    ];

    protected $with = ['media'];

    protected $casts = [
        'read_at' => 'datetime',
        'attachments' => 'array',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ProjectConversation::class, 'conversation_id');
    }

    public function sender(): MorphTo
    {
        return $this->morphTo();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProjectMessage::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ProjectMessage::class, 'parent_id');
    }

    public function reads(): HasMany
    {
        return $this->hasMany(ChatMessageRead::class, 'message_id');
    }
}
