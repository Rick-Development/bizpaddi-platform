<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Admin;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Traits\BelongsToTenant;

class Task extends Model implements HasMedia
{
    use BelongsToTenant;
    use HasFactory, InteractsWithMedia;

    protected $table = 'pm_tasks';

    protected $fillable = [
        'tenant_id',
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'assigned_to',
        'assigned_by',
        'position',
        'requires_client_approval',
        'approval_status',
        'client_feedback',
        'approved_at',
        'milestone_id',
        'order',
        'status_changed_at',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'position' => 'integer',
        'requires_client_approval' => 'boolean',
        'approved_at' => 'datetime',
        'milestone_id' => 'integer',
        'order' => 'integer',
        'completed_at' => 'datetime',
        'status_changed_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::saving(function ($task) {
            if ($task->isDirty('status')) {
                $task->status_changed_at = now();
            }
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    public function assignee()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }

    public function assignedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_by');
    }

    public function timeLogs(): HasMany
    {
        return $this->hasMany(TimeLog::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    public function getTotalTimeSpentAttribute(): int
    {
        return $this->timeLogs()->sum('duration_minutes');
    }

    public function getActiveTimerAttribute()
    {
        $user = auth()->user() ?: auth('admin')->user() ?: auth('client')->user();

        if (!$user) {
            return null;
        }

        return $this->timeLogs()
            ->where('user_id', $user->id)
            ->where('user_type', $user->getMorphClass())
            ->whereNull('end_time')
            ->first();
    }

    public function formatDuration($minutes): string
    {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        return sprintf('%02dh %02dm', $hours, $mins);
    }
}
