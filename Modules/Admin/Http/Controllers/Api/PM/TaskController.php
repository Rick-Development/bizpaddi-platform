<?php

namespace Modules\Admin\Http\Controllers\Api\PM;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Api\ApiController;
use Modules\ProjectManagement\Models\Task;

class TaskController extends ApiController
{
    /**
     * GET /api/v1/admin/pm/tasks
     */
    public function index(Request $request)
    {
        $query = Task::with(['project', 'assignee', 'milestone'])->latest();

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->has('milestone_id')) {
            $query->where('milestone_id', $request->milestone_id);
        }
        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->paginate(30);
        return $this->sendResponse($tasks, 'Tasks retrieved successfully.');
    }

    /**
     * POST /api/v1/admin/pm/tasks
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id'               => 'required|exists:pm_projects,id',
            'milestone_id'             => 'nullable|exists:pm_milestones,id',
            'title'                    => 'required|string|max:255',
            'description'              => 'nullable|string',
            'status'                   => 'nullable|string|in:todo,in_progress,review,done',
            'priority'                 => 'nullable|string|in:low,medium,high,urgent',
            'due_date'                 => 'nullable|date',
            'assigned_to'              => 'nullable|exists:users,id',
            'position'                 => 'nullable|integer|min:0',
            'order'                    => 'nullable|integer|min:0',
            'requires_client_approval' => 'nullable|boolean',
        ]);

        $validated['assigned_by'] = $request->user()->id;

        if (!isset($validated['status'])) {
            $validated['status'] = 'todo';
        }
        if (!isset($validated['priority'])) {
            $validated['priority'] = 'medium';
        }

        $task = Task::create($validated);
        return $this->sendResponse($task->load(['project', 'assignee', 'milestone']), 'Task created successfully.', 201);
    }

    /**
     * GET /api/v1/admin/pm/tasks/{id}
     */
    public function show($id)
    {
        $task = Task::with(['project', 'assignee', 'assignedBy', 'milestone', 'comments', 'timeLogs'])->find($id);
        if (!$task) return $this->sendError('Task not found.', 404);
        return $this->sendResponse($task, 'Task details retrieved successfully.');
    }

    /**
     * PUT /api/v1/admin/pm/tasks/{id}
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task) return $this->sendError('Task not found.', 404);

        $validated = $request->validate([
            'project_id'               => 'sometimes|exists:pm_projects,id',
            'milestone_id'             => 'nullable|exists:pm_milestones,id',
            'title'                    => 'sometimes|string|max:255',
            'description'              => 'nullable|string',
            'status'                   => 'nullable|string|in:todo,in_progress,review,done',
            'priority'                 => 'nullable|string|in:low,medium,high,urgent',
            'due_date'                 => 'nullable|date',
            'assigned_to'              => 'nullable|exists:users,id',
            'position'                 => 'nullable|integer|min:0',
            'order'                    => 'nullable|integer|min:0',
            'requires_client_approval' => 'nullable|boolean',
            'approval_status'          => 'nullable|string|in:pending,approved,rejected',
            'client_feedback'          => 'nullable|string',
        ]);

        $task->update($validated);
        return $this->sendResponse($task->fresh()->load(['project', 'assignee', 'milestone']), 'Task updated successfully.');
    }

    /**
     * PATCH /api/v1/admin/pm/tasks/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task) return $this->sendError('Task not found.', 404);

        $validated = $request->validate([
            'status' => 'required|string|in:todo,in_progress,review,done',
        ]);

        $task->update(['status' => $validated['status']]);

        return $this->sendResponse(
            ['id' => $task->id, 'status' => $task->fresh()->status],
            'Task status updated successfully.'
        );
    }

    /**
     * DELETE /api/v1/admin/pm/tasks/{id}
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        if (!$task) return $this->sendError('Task not found.', 404);
        $task->delete();
        return $this->sendResponse([], 'Task deleted successfully.');
    }
}
