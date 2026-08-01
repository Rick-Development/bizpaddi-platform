<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\Admin;

class TimeLog extends Model
{
    use HasFactory;

    protected $table = 'pm_time_logs';

    protected $fillable = [
        'task_id',
        'user_id',
        'user_type',
        'start_time',
        'end_time',
        'duration_minutes',
        'description',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): MorphTo
    {
        return $this->morphTo();
    }
}
