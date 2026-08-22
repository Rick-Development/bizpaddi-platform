<?php

namespace App\Observers;

use Modules\TeamPortal\Models\WorkLog;
use App\Notifications\OperationalMailNotification;

class WorkLogObserver
{
    /**
     * Handle the WorkLog "updated" event.
     */
    public function updated(WorkLog $workLog): void
    {
        // Check if session status changed to 'completed' (checkout)
        if ($workLog->wasChanged('status') && $workLog->status === 'completed') {
            $user = $workLog->user;
            if ($user) {
                // Calculate completed tasks in this session from tasks_data
                $completedCount = 0;
                $tasksData = $workLog->tasks_data;
                if (is_array($tasksData)) {
                    foreach ($tasksData as $t) {
                        $result = strtolower($t['result'] ?? $t['status'] ?? '');
                        if (in_array($result, ['completed', 'done'])) {
                            $completedCount++;
                        }
                    }
                }

                // Send session summary notification to user
                $user->notify(new OperationalMailNotification('Work Session Summary', [
                    'name' => $user->name,
                    'start_time' => $workLog->start_time ? $workLog->start_time->format('Y-m-d H:i') : 'N/A',
                    'end_time' => $workLog->end_time ? $workLog->end_time->format('Y-m-d H:i') : 'N/A',
                    'duration' => $workLog->duration_minutes ?? 0,
                    'completed_tasks' => $completedCount,
                    'points_earned' => $completedCount * 2, // 2 points per completed task
                ]));
            }
        }
    }
}
