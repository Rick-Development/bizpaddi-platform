<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\TeamPortal\Models\WorkLog;

class WorkLogController extends ApiController
{
    /**
     * GET /api/v1/team/work-logs
     * Get active work log session and completed history.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $activeLog = $user->workLogs()
            ->where('status', 'in_progress')
            ->latest()
            ->first();

        $history = $user->workLogs()
            ->where('status', 'completed')
            ->latest()
            ->paginate(10);

        return $this->sendResponse([
            'active_session' => $activeLog,
            'history'        => $history,
        ], 'Work logs retrieved successfully.');
    }

    /**
     * POST /api/v1/team/work-logs/check-in
     * Start a new work session.
     */
    public function checkIn(Request $request)
    {
        $user = $request->user();

        if ($user->workLogs()->where('status', 'in_progress')->exists()) {
            return $this->sendError('You already have an active work session.', 400);
        }

        $isPM = method_exists($user, 'isProjectManager') && $user->isProjectManager();

        if ($isPM) {
            $request->validate([
                'planned_projects'    => 'required|array',
                'session_goal'        => 'required|string',
                'planned_assignments' => 'nullable|array',
                'no_assignments_planned' => 'nullable|boolean|required_without:planned_assignments',
            ]);

            $log = $user->workLogs()->create([
                'start_time'          => now(),
                'planned_tasks'       => 'PM Session Goal: ' . $request->session_goal,
                'planned_projects'    => $request->planned_projects,
                'session_goal'        => $request->session_goal,
                'planned_assignments' => $request->planned_assignments ?? [],
                'status'              => 'in_progress',
            ]);
        } else {
            $request->validate([
                'planned_projects' => 'required|array',
                'planned_tasks'    => 'required|array',
                'resources_needed' => 'nullable|string',
            ]);

            $log = $user->workLogs()->create([
                'start_time'       => now(),
                'planned_tasks'    => implode(', ', $request->planned_tasks),
                'planned_projects' => $request->planned_projects,
                'resources_needed' => $request->resources_needed,
                'status'           => 'in_progress',
            ]);
        }

        return $this->sendResponse($log, 'Check-in successful.', 201);
    }

    /**
     * PUT /api/v1/team/work-logs/{id}/check-out
     * End an active work session.
     */
    public function checkOut(Request $request, $id)
    {
        $user = $request->user();

        $workLog = WorkLog::where('user_id', $user->id)
            ->where('id', $id)
            ->where('status', 'in_progress')
            ->first();

        if (!$workLog) {
            return $this->sendError('Active work session not found.', 404);
        }

        $isPM = method_exists($user, 'isProjectManager') && $user->isProjectManager();
        $endTime = now();

        if ($isPM) {
            $request->validate([
                'key_issues'              => 'required|string',
                'requires_mgmt_attention' => 'required|boolean',
                'mgmt_attention_details'  => 'required_if:requires_mgmt_attention,true|nullable|string',
            ]);

            // Calculate auto-computed PM stats since session start
            $tasksAssignedCount = \Modules\ProjectManagement\Models\Task::where('assigned_by', $user->id)
                ->where('created_at', '>=', $workLog->start_time)
                ->count();

            $tasksClosedCount = \Modules\ProjectManagement\Models\Task::where('assigned_to', $user->id)
                ->where('status', 'completed')
                ->where('updated_at', '>=', $workLog->start_time)
                ->count();

            $workLog->update([
                'end_time'     => $endTime,
                'actual_tasks' => sprintf(
                    "PM Session Completed. Goal recap: %s\nTasks Assigned: %d\nTasks Closed: %d\nIssues: %s\nMgmt Attention Required: %s",
                    $workLog->session_goal,
                    $tasksAssignedCount,
                    $tasksClosedCount,
                    $request->key_issues,
                    $request->requires_mgmt_attention ? 'Yes (' . $request->mgmt_attention_details . ')' : 'No'
                ),
                'challenges'   => $request->key_issues,
                'kpi_metrics'  => [
                    'tasks_assigned' => $tasksAssignedCount,
                    'tasks_closed'   => $tasksClosedCount,
                    'requires_mgmt_attention' => $request->requires_mgmt_attention,
                    'mgmt_attention_details' => $request->mgmt_attention_details,
                ],
                'status'       => 'completed',
            ]);
        } else {
            $request->validate([
                'actual_tasks' => 'required|string',
                'challenges'   => 'nullable|string',
                'kpi_metrics'  => 'nullable|array',
                'tasks_data'   => 'nullable|array',
            ]);

            $tasksData = $request->tasks_data;

            if (is_array($tasksData) && !empty($tasksData)) {
                $processedTasks = [];
                foreach ($tasksData as $task) {
                    $taskId = $task['task_id'] ?? null;
                    $result = $task['result'] ?? null;
                    $evidence = $task['evidence'] ?? null;

                    if (!$taskId || empty($result)) {
                        continue;
                    }

                    // Tiered Evidence validations
                    if ($result === 'Completed' && empty($evidence)) {
                        return $this->sendError("Evidence is required for completed task (ID: {$taskId}).", 400);
                    }

                    if ($result === 'Partial') {
                        if ($workLog->hasPartialWithoutEvidenceThreshold($taskId, 3) && empty($evidence)) {
                            return $this->sendError("Evidence is required for task (ID: {$taskId}) as it has been partial for 3+ consecutive sessions.", 400);
                        }
                    }

                    if ($result === 'Blocked') {
                        if (empty($task['blocker_details']['blocked_by']) || empty($task['blocker_details']['blocking_item'])) {
                            return $this->sendError("Blocker details (blocked_by, blocking_item) are required for blocked task (ID: {$taskId}).", 400);
                        }
                    }

                    // Dynamic duration calculation from pm_time_logs
                    $duration = \Modules\ProjectManagement\Models\TimeLog::where('task_id', $taskId)
                        ->where('user_id', $user->id)
                        ->where('start_time', '>=', $workLog->start_time)
                        ->sum('duration_minutes');

                    $task['time_spent_minutes'] = (int)$duration;
                    $processedTasks[] = $task;
                }
                $tasksData = !empty($processedTasks) ? $processedTasks : null;
            } else {
                $tasksData = null;
            }

            $workLog->update([
                'end_time'     => $endTime,
                'actual_tasks' => $request->actual_tasks,
                'challenges'   => $request->challenges,
                'tasks_data'   => $tasksData,
                'kpi_metrics'  => $request->kpi_metrics,
                'status'       => 'completed',
            ]);
        }

        return $this->sendResponse($workLog, 'Check-out successful.');
    }

    public function checkPartialStatus(Request $request)
    {
        $request->validate([
            'task_id' => 'required|integer',
        ]);

        $dummyLog = new WorkLog(['user_id' => $request->user()->id]);
        $isStale = $dummyLog->hasPartialWithoutEvidenceThreshold($request->task_id, 3);

        return $this->sendResponse([
            'is_stale_partial' => $isStale
        ], 'Partial status checked.');
    }
}
