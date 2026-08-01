<?php

namespace Modules\TeamPortal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class WorkLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'duration_minutes',
        'planned_tasks',
        'resources_needed',
        'actual_tasks',
        'challenges',
        'kpi_metrics',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'kpi_metrics' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper to calculate duration on save if end_time is present
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($workLog) {
            if ($workLog->end_time && $workLog->start_time) {
                $workLog->duration_minutes = $workLog->start_time->diffInMinutes($workLog->end_time);
            }
        });
    }
}
