<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Traits\BelongsToTenant;
use App\Scopes\TenantScope;

class Comment extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, BelongsToTenant;

    protected $table = 'pm_comments';

    protected $fillable = [
        'tenant_id',
        'commentable_id',
        'commentable_type',
        'user_id',
        'user_type',
        'content',
        'is_internal',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            if (empty($comment->tenant_id)) {
                $tenantId = TenantScope::getActiveTenantId();
                if (!$tenantId && $comment->commentable && !empty($comment->commentable->tenant_id)) {
                    $tenantId = $comment->commentable->tenant_id;
                }
                if ($tenantId) {
                    $comment->tenant_id = $tenantId;
                }
            }
        });
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'user_type', 'user_id');
    }
}
