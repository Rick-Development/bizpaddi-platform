<?php

namespace Modules\Investor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ShareholderMeeting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'meeting_type',
        'meeting_date',
        'location',
        'description',
        'agenda',
        'is_upcoming',
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
        'is_upcoming' => 'boolean',
    ];

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('agenda_file')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf']);

        $this->addMediaCollection('minutes_file')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf']);

        $this->addMediaCollection('presentation_file')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation']);
    }

    /**
     * Scope for upcoming meetings
     */
    public function scopeUpcoming($query)
    {
        return $query->where('is_upcoming', true)
            ->where('meeting_date', '>=', now())
            ->orderBy('meeting_date', 'asc');
    }

    /**
     * Scope for past meetings
     */
    public function scopePast($query)
    {
        return $query->where(function ($q) {
            $q->where('is_upcoming', false)
              ->orWhere('meeting_date', '<', now());
        })->orderBy('meeting_date', 'desc');
    }

    /**
     * Scope for filtering by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('meeting_type', $type);
    }

    /**
     * Check if meeting is in the past
     */
    public function getIsPastAttribute()
    {
        return $this->meeting_date < now();
    }
}
