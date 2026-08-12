<?php

namespace Modules\SocialMedia\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmmCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'platforms', 'status',
        'start_date', 'end_date', 'goals',
    ];

    protected $casts = [
        'platforms'  => 'array',
        'goals'      => 'array',
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function posts()
    {
        return $this->hasMany(SmmPost::class, 'campaign_id');
    }

    public function analytics()
    {
        return $this->hasMany(SmmAnalytic::class, 'campaign_id');
    }

    /** Progress percentage based on dates */
    public function getProgressAttribute(): int
    {
        if (!$this->start_date || !$this->end_date) return 0;
        $total   = $this->start_date->diffInDays($this->end_date);
        $elapsed = $this->start_date->diffInDays(now());
        if ($total <= 0) return 100;
        return min(100, (int) round(($elapsed / $total) * 100));
    }
}
