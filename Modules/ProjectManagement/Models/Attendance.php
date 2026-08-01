<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'pm_attendance';

    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'duration_minutes',
        'status',
        'work_mode',
        'ip_address',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getDurationFormattedAttribute(): string
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        return sprintf('%02dh %02dm', $hours, $minutes);
    }
}
