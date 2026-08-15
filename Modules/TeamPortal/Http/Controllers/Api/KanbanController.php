<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Task;

class KanbanController extends ApiController
{
    /**
     * GET /api/v1/team/kanban
     * Return tasks grouped by status for Kanban board.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $columns = ['todo', 'in_progress', 'review', 'done'];
        $board   = [];

        foreach ($columns as $status) {
            $board[$status] = Task::with(['project:id,name'])
                ->where('assigned_to', $userId)
                ->where('status', $status)
                ->orderBy('order')
                ->get()
                ->map(fn($t) => [
                    'id'       => $t->id,
                    'title'    => $t->title,
                    'priority' => $t->priority,
                    'due_date' => $t->due_date?->toDateString(),
                    'project'  => $t->project?->only(['id', 'name']),
                    'order'    => $t->order,
                ]);
        }

        return $this->sendResponse($board, 'Kanban board retrieved successfully.');
    }

    /**
     * POST /api/v1/team/kanban/update-order
     * Update the order and/or status of tasks on the Kanban board.
     *
     * Body: { "tasks": [{ "id": 1, "status": "in_progress", "order": 0 }] }
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'tasks'          => 'required|array',
            'tasks.*.id'     => 'required|integer|exists:pm_tasks,id',
            'tasks.*.status' => 'required|in:todo,in_progress,review,done',
            'tasks.*.order'  => 'required|integer',
        ]);

        $userId = $request->user()->id;

        $user = $request->user();
        foreach ($request->tasks as $item) {
            $task = Task::where('id', $item['id'])
                ->where('assigned_to', $userId)
                ->first();

            if ($task) {
                $newStatus = $item['status'];
                if ($newStatus === 'done' && $task->status !== 'done' && !$user->isProjectManager()) {
                    return $this->sendError('Only Project Managers can mark tasks as Completed.', 403);
                }

                $task->update([
                    'status' => $newStatus,
                    'order'  => $item['order'],
                ]);
            }
        }

        return $this->sendResponse([], 'Kanban order updated successfully.');
    }
}
