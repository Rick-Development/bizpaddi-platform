<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Task;
use Modules\ProjectManagement\Models\Project;

class KanbanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = Task::with(['project', 'assignee']);
        
        if ($user->isProjectManager()) {
            // PMs see tasks assigned to them OR tasks in projects they manage
            $query->where(function($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->orWhereHas('project', function($pq) use ($user) {
                      $pq->where('manager_id', $user->id);
                  });
            });
        } else {
            // Regular members only see tasks assigned to them
            $query->where('assigned_to', $user->id);
        }
        
        $tasks = $query->orderBy('order')->get();

        $columns = [
            'todo' => 'To Do',
            'in_progress' => 'In Progress',
            'review' => 'Done (Waiting for Review)',
            'done' => 'Completed',
        ];

        $groupedTasks = $tasks->groupBy('status');

        return view('teamportal::kanban.index', compact('groupedTasks', 'columns'));
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'tasks' => 'required|array',
            'tasks.*.id' => 'required|exists:pm_tasks,id',
            'tasks.*.status' => 'required|in:todo,in_progress,review,done',
            'tasks.*.order' => 'required|integer',
        ]);

        foreach ($request->tasks as $taskData) {
            $task = Task::with('project')->find($taskData['id']);
            if ($task) {
                // Allow update if assigned to user OR user manages the project
                if ($task->assigned_to == auth()->id() || ($task->project && $task->project->manager_id == auth()->id())) {
                    $newStatus = $taskData['status'];
                    if ($newStatus === 'done' && $task->status !== 'done' && !auth()->user()->isProjectManager()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Only Project Managers can mark tasks as Completed.'
                        ], 403);
                    }

                    $task->update([
                        'status' => $newStatus,
                        'order' => $taskData['order'],
                    ]);
                }
            }
        }

        return response()->json(['success' => true]);
    }
}
