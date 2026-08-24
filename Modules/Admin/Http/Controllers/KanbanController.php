<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Task;
use Modules\ProjectManagement\Models\Project;

class KanbanController extends Controller
{
    public function index(Request $request)
    {
        $projectId = $request->project_id;
        $project = $projectId ? Project::find($projectId) : null;
        
        $tasksQuery = Task::with(['assignee', 'project'])
            ->when($projectId, function($q) use ($projectId) {
                return $q->where('project_id', $projectId);
            })
            ->when($request->priority, function($q, $priority) {
                return $q->where('priority', $priority);
            })
            ->when($request->assigned_to, function($q, $assigneeId) {
                return $q->where('assigned_to', $assigneeId);
            })
            ->when($request->search, function($q, $search) {
                return $q->where(function($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            });

        if ($request->filled('date_filter')) {
            $dateFilter = $request->date_filter;
            if ($dateFilter === 'today') {
                $tasksQuery->whereDate('created_at', \Carbon\Carbon::today());
            } elseif ($dateFilter === 'yesterday') {
                $tasksQuery->whereDate('created_at', \Carbon\Carbon::yesterday());
            } elseif ($dateFilter === 'this_week') {
                $tasksQuery->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
            } elseif ($dateFilter === 'this_month') {
                $tasksQuery->whereMonth('created_at', \Carbon\Carbon::now()->month)
                           ->whereYear('created_at', \Carbon\Carbon::now()->year);
            } elseif ($dateFilter === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
                $tasksQuery->whereBetween('created_at', [
                    \Carbon\Carbon::parse($request->start_date)->startOfDay(),
                    \Carbon\Carbon::parse($request->end_date)->endOfDay()
                ]);
            }
        }

        $tasks = $tasksQuery->orderBy('order')->get();

        $columns = [
            'todo' => 'To Do',
            'in_progress' => 'In Progress',
            'review' => 'Review',
            'done' => 'Completed',
        ];

        $groupedTasks = $tasks->groupBy('status');
        
        if ($request->ajax()) {
            return view('admin::kanban.board', compact('groupedTasks', 'columns'))->render();
        }
        
        $projects = Project::all();
        $assignees = \App\Models\User::where('status', 'active')->orderBy('name')->get();

        return view('admin::kanban.index', compact('groupedTasks', 'columns', 'projects', 'project', 'assignees'));
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
            $task = Task::find($taskData['id']);
            if ($task) {
                $task->update([
                    'status' => $taskData['status'],
                    'order' => $taskData['order'],
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}
