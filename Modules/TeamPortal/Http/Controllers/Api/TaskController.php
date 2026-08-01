<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Task;

class TaskController extends ApiController
{
    /**
     * GET /api/v1/team/tasks
     * List all tasks assigned to the authenticated team member.
     */
    public function index(Request $request)
    {
        $tasks = Task::with(['project:id,name', 'milestone:id,name'])
            ->where('assigned_to', $request->user()->id)
            ->latest()
            ->paginate(15);

        return $this->sendResponse($tasks, 'Tasks retrieved successfully.');
    }

    /**
     * GET /api/v1/team/tasks/{id}
     * Get details of a specific task.
     */
    public function show(Request $request, $id)
    {
        $task = Task::with(['project', 'milestone', 'assignedBy:id,name', 'comments'])
            ->where('assigned_to', $request->user()->id)
            ->find($id);

        if (!$task) {
            return $this->sendError('Task not found.', 404);
        }

        return $this->sendResponse($task, 'Task details retrieved successfully.');
    }

    /**
     * PATCH /api/v1/team/tasks/{id}/status
     * Update the status of a task.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        $task = Task::where('assigned_to', $request->user()->id)->find($id);

        if (!$task) {
            return $this->sendError('Task not found.', 404);
        }

        $task->update(['status' => $request->status]);

        return $this->sendResponse($task->only(['id', 'status']), 'Task status updated successfully.');
    }
}
