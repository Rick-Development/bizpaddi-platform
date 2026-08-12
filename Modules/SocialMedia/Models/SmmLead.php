<?php

namespace Modules\SocialMedia\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmmLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_platform', 'user_handle', 'context',
        'intent_level', 'status', 'detected_at',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
    ];

    /** Badge color for the inbox UI */
    public function getIntentBadgeClassAttribute(): string
    {
        return match ($this->intent_level) {
            'high'   => 'background:#fee2e2;color:#b91c1c;',
            'medium' => 'background:#fef9c3;color:#a16207;',
            default  => 'background:#f3f4f6;color:#555;',
        };
    }
}
