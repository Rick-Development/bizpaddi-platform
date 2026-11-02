<?php

namespace Modules\TeamPortal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Traits\BelongsToTenant;

class WorkLog extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'start_time',
        'end_time',
        'duration_minutes',
        'planned_tasks',
        'resources_needed',
        'planned_projects',
        'session_goal',
        'planned_assignments',
        'tasks_data',
        'actual_tasks',
        'challenges',
        'kpi_metrics',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'planned_projects' => 'array',
        'planned_assignments' => 'array',
        'tasks_data' => 'array',
        'kpi_metrics' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Checks if this task was marked "Partial" for the last $threshold consecutive work sessions
     * by the user where the task was touched, without any evidence being supplied.
     */
    public function hasPartialWithoutEvidenceThreshold($taskId, $threshold = 3)
    {
        $pastLogs = self::where('user_id', $this->user_id)
            ->where('status', 'completed')
            ->whereNotNull('tasks_data')
            ->orderBy('end_time', 'desc')
            ->get();

        $consecutivePartialCount = 0;

        foreach ($pastLogs as $log) {
            $tasks = $log->tasks_data;
            if (!is_array($tasks)) {
                continue;
            }

            // Find the task entry inside this session's tasks_data
            $taskEntry = null;
            foreach ($tasks as $t) {
                if (isset($t['task_id']) && $t['task_id'] == $taskId) {
                    $taskEntry = $t;
                    break;
                }
            }

            if ($taskEntry) {
                $result = $taskEntry['result'] ?? null;
                $evidence = $taskEntry['evidence'] ?? null;

                if ($result === 'Partial' && empty($evidence)) {
                    $consecutivePartialCount++;
                    if ($consecutivePartialCount >= $threshold) {
                        return true;
                    }
                } else {
                    // Streak broken (either it was Completed, Blocked, or evidence was uploaded)
                    break;
                }
            }
        }

        return false;
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
