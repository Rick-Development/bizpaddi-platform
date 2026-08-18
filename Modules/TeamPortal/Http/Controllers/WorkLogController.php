<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\TeamPortal\Models\WorkLog;
use Modules\ProjectManagement\Models\Project;
use Modules\ProjectManagement\Models\Task;
use Modules\ProjectManagement\Models\TimeLog;

class WorkLogController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Check for active session
        $activeLog = $user->workLogs()
                          ->where('status', 'in_progress')
                          ->latest()
                          ->first();

        // Get history
        $history = $user->workLogs()
                        ->where('status', 'completed')
                        ->latest()
                        ->paginate(10);

        // Fetch projects and tasks
        $projects = Project::all();
        
        $pmAvailableTasks = collect();
        $tasksClosedCount = 0;
        if ($activeLog && !empty($activeLog->planned_tasks)) {
            // Checked in: load only the tasks selected during check-in
            $titles = array_filter(array_map('trim', explode(',', $activeLog->planned_tasks)));
            $assignedTasks = Task::with(['project', 'assignee'])
                ->whereIn('title', $titles)
                ->get();
            
            if (method_exists($user, 'isProjectManager') && $user->isProjectManager()) {
                $projectIds = $activeLog->planned_projects ?? [];
                $pmAvailableTasks = Task::with(['project', 'assignee'])
                    ->whereIn('project_id', $projectIds)
                    ->whereIn('status', ['todo', 'in_progress', 'pending', 'review'])
                    ->get();
                $tasksClosedCount = Task::whereIn('project_id', $projectIds)
                    ->where('status', 'completed')
                    ->where('updated_at', '>=', $activeLog->start_time)
                    ->count();
            }
        } else {
            // Not checked in: load all open tasks to allow project-filtered check-in
            $assignedTasks = Task::with(['project', 'assignee'])
                ->whereIn('status', ['todo', 'in_progress', 'pending', 'review'])
                ->get();
        }

        return view('teamportal::work-logs.index', compact('activeLog', 'history', 'projects', 'assignedTasks', 'pmAvailableTasks', 'tasksClosedCount'));
    }

    public function checkIn(Request $request)
    {
        $user = auth()->user();

        // Prevent double check-in
        if ($user->workLogs()->where('status', 'in_progress')->exists()) {
             return redirect()->back()->with('error', 'You already have an active work session.');
        }

        $isPM = method_exists($user, 'isProjectManager') && $user->isProjectManager();

        if ($isPM) {
            $request->validate([
                'planned_projects'       => 'required|array',
                'session_goal'           => 'required|string',
                'planned_assignments'    => 'nullable|array',
                'no_assignments_planned' => 'nullable|boolean|required_without:planned_assignments',
            ]);

            $user->workLogs()->create([
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

            $user->workLogs()->create([
                'start_time'       => now(),
                'planned_tasks'    => implode(', ', $request->planned_tasks),
                'planned_projects' => $request->planned_projects,
                'resources_needed' => $request->resources_needed,
                'status'           => 'in_progress',
            ]);
        }

        return redirect()->route('team.work-logs.index')->with('success', 'You have successfully checked in.');
    }

    public function checkOut(Request $request, WorkLog $workLog)
    {
        $user = auth()->user();

        abort_unless($workLog->user()->is($user), 403);
        abort_unless($workLog->status === 'in_progress', 403);

        $isPM = method_exists($user, 'isProjectManager') && $user->isProjectManager();
        $endTime = now();

        if ($isPM) {
            $request->merge([
                'requires_mgmt_attention' => $request->has('requires_mgmt_attention') ? (bool)$request->requires_mgmt_attention : false
            ]);

            $request->validate([
                'key_issues'              => 'required|string',
                'requires_mgmt_attention' => 'required|boolean',
                'mgmt_attention_details'  => 'required_if:requires_mgmt_attention,true|nullable|string',
                'actual_assignments'      => 'nullable|array',
            ]);

            $actualAssignments = $request->actual_assignments ?? [];
            $tasksAssignedCount = count($actualAssignments);

            $tasksClosedCount = Task::where('assigned_to', $user->id)
                ->where('status', 'completed')
                ->where('updated_at', '>=', $workLog->start_time)
                ->count();

            $tasksData = [];
            if (!empty($actualAssignments)) {
                $assignedTaskModels = Task::whereIn('id', $actualAssignments)->get();
                foreach ($assignedTaskModels as $t) {
                    $tasksData[] = [
                        'task_id' => $t->id,
                        'title' => $t->title,
                        'result' => 'Assigned',
                    ];
                }
            }

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
                    'assigned_task_ids' => $actualAssignments,
                ],
                'tasks_data'   => $tasksData,
                'status'       => 'completed',
            ]);
        } else {
            $request->validate([
                'actual_tasks' => 'nullable|string|required_without:actual_tasks_encoded',
                'actual_tasks_encoded' => 'nullable|string|required_without:actual_tasks',
                'challenges'   => 'nullable|string',
                'challenges_encoded' => 'nullable|string',
                'kpi_metrics'  => 'nullable|array',
                'tasks_data'   => 'required|array',
            ]);

            $actualTasks = $this->decodeWorkLogField(
                $request->input('actual_tasks_encoded'),
                $request->input('actual_tasks')
            );
            $challenges = $this->decodeWorkLogField(
                $request->input('challenges_encoded'),
                $request->input('challenges')
            );

            if ($actualTasks === null || trim($actualTasks) === '') {
                return back()->withErrors(['actual_tasks' => 'The work completed summary field is required.'])->withInput();
            }

            $tasksData = $request->tasks_data;

            foreach ($tasksData as &$task) {
                $taskId = $task['task_id'] ?? null;
                $result = $task['result'] ?? null;
                $evidence = $task['evidence'] ?? null;

                if (!$taskId || !$result) {
                    return back()->withErrors(['tasks_data' => 'Invalid task data. Each task must have a result.'])->withInput();
                }

                // Tiered Evidence validation
                if ($result === 'Completed' && empty($evidence)) {
                    return back()->withErrors(["tasks_data" => "Evidence is required for completed task (ID: {$taskId})."])->withInput();
                }

                if ($result === 'Partial') {
                    if ($workLog->hasPartialWithoutEvidenceThreshold($taskId, 3) && empty($evidence)) {
                        return back()->withErrors(["tasks_data" => "Evidence is required for task (ID: {$taskId}) as it has been partial for 3+ consecutive sessions."])->withInput();
                    }
                }

                if ($result === 'Blocked') {
                    if (empty($task['blocker_details']['blocked_by']) || empty($task['blocker_details']['blocking_item'])) {
                        return back()->withErrors(["tasks_data" => "Blocker details are required for blocked task (ID: {$taskId})."])->withInput();
                    }
                }

                // Calculate time spent from pm_time_logs
                $duration = TimeLog::where('task_id', $taskId)
                    ->where('user_id', $user->id)
                    ->where('start_time', '>=', $workLog->start_time)
                    ->sum('duration_minutes');

                $task['time_spent_minutes'] = (int)$duration;
            }

            $workLog->update([
                'end_time'     => $endTime,
                'actual_tasks' => $actualTasks,
                'challenges'   => $challenges,
                'tasks_data'   => $tasksData,
                'kpi_metrics'  => $request->kpi_metrics,
                'status'       => 'completed',
            ]);
        }

        return redirect()->route('team.work-logs.index')->with('success', 'You have checked out successfully. Great work!');
    }

    public function show(WorkLog $workLog)
    {
        abort_unless($workLog->user()->is(auth()->user()), 403);
        $workLog->load('user');
        return view('teamportal::work-logs.show', compact('workLog'));
    }

    public function checkPartialStatus(Request $request)
    {
        $request->validate([
            'task_id' => 'required|integer',
        ]);

        $dummyLog = new WorkLog(['user_id' => auth()->id()]);
        $isStale = $dummyLog->hasPartialWithoutEvidenceThreshold($request->task_id, 3);

        return response()->json([
            'is_stale_partial' => $isStale
        ]);
    }

    private function decodeWorkLogField(?string $encodedValue, ?string $fallbackValue): ?string
    {
        if ($encodedValue === null || $encodedValue === '') {
            return $fallbackValue;
        }

        $decoded = base64_decode($encodedValue, true);

        return $decoded === false ? $fallbackValue : $decoded;
    }
}
