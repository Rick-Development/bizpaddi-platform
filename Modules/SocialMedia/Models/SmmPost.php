<?php

namespace Modules\SocialMedia\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmmPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id', 'calendar_id', 'platform', 'content',
        'media_urls', 'hashtags', 'status', 'scheduled_for',
    ];

    protected $casts = [
        'media_urls'    => 'array',
        'hashtags'      => 'array',
        'scheduled_for' => 'datetime',
    ];

    public function analytics()
    {
        return $this->hasMany(SmmAnalytic::class, 'post_id');
    }

    public function campaign()
    {
        return $this->belongsTo(SmmCampaign::class, 'campaign_id');
    }

    /** Human-readable platform label */
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

    /** CSS chip class for the calendar */
    public function getChipClassAttribute(): string
    {
        return match ($this->platform) {
            'linkedin'  => 'chip-li',
            'x'         => 'chip-x',
            'instagram' => 'chip-ig',
            'facebook'  => 'chip-fb',
            default     => 'chip-ai',
        };
    }
}
