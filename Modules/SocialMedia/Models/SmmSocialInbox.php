<?php

namespace Modules\SocialMedia\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmmSocialInbox extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform', 'external_id', 'sender_name',
        'message', 'category', 'sentiment', 'status',
    ];

    /** Badge label and style for the inbox thread list */
    public function getCategoryBadgeAttribute(): array
    {
        return match ($this->category) {
            'escalated' => ['label' => '⚠️ Escalated', 'class' => 'ti-escalated'],
            'lead'      => ['label' => '🔥 Lead',      'class' => 'ti-lead'],
            'mention'   => ['label' => '💬 Mention',   'class' => 'ti-mention'],
            default     => ['label' => '💬 Comment',   'class' => 'ti-comment'],
        };
    }

    /** Map platform key to display label */
    public function getPlatformLabelAttribute(): string
    {
        return match ($this->platform) {
            'linkedin'  => 'LinkedIn',
            'x'         => 'X (Twitter)',
            'instagram' => 'Instagram',
            'facebook'  => 'Facebook',
            default     => ucfirst($this->platform),
        };
    }
}
