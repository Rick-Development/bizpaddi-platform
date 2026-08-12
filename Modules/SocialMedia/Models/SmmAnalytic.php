<?php

namespace Modules\SocialMedia\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmmAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id', 'campaign_id', 'platform',
        'impressions', 'clicks', 'likes', 'shares', 'comments',
        'engagement_rate', 'tracked_at',
    ];

    protected $casts = [
        'tracked_at' => 'datetime',
    ];

    public function post()
    {
        return $this->belongsTo(SmmPost::class, 'post_id');
    }
}
