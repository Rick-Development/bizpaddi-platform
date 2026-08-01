<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Comment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'pm_comments';

    protected $fillable = [
        'commentable_id',
        'commentable_type',
        'user_id',
        'user_type',
        'content',
        'is_internal',
    ];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        // This is a simplified relation, might need adjustment based on multiauth
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
