<?php

namespace Modules\TeamPortal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BugReport extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'project_id', 'task_id', 'reported_by', 'assigned_to',
        'title', 'description', 'severity', 'priority', 'status',
        'due_date',
        'environment', 'browser', 'steps_to_reproduce',
        'expected_result', 'actual_result', 'resolution_notes', 'resolved_at',
    ];

    protected $casts = [
        'due_date'    => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && !in_array($this->status, ['resolved', 'closed', 'wont_fix']);
    }

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function project()
    {
        return $this->belongsTo(\Modules\ProjectManagement\Models\Project::class);
    }

    public function task()
    {
        return $this->belongsTo(\Modules\ProjectManagement\Models\Task::class);
    }

    public function reporter()
    {
        return $this->belongsTo(\App\Models\User::class, 'reported_by');
    }

    public function assignee()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_to');
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('reported_by', $userId);
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────

    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            'critical' => 'red',
            'high'     => 'orange',
            'medium'   => 'yellow',
            'low'      => 'green',
            default    => 'gray',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'open'        => 'blue',
            'in_progress' => 'yellow',
            'resolved'    => 'green',
            'closed'      => 'gray',
            'wont_fix'    => 'red',
            default       => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'open'        => 'Open',
            'in_progress' => 'In Progress',
            'resolved'    => 'Resolved',
            'closed'      => 'Closed',
            'wont_fix'    => "Won't Fix",
            default       => ucfirst($this->status),
        };
    }
}
