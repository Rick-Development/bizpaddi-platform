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
        
        $query = Task::with(['project', 'assignee', 'milestone']);
        
        if ($user->isAdmin()) {
            // Admins can view all tasks
        } elseif ($user->isProjectManager()) {
            // PMs see tasks assigned to them OR tasks in projects they manage OR projects where they are team members
            $query->where(function($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->orWhereHas('project', function($pq) use ($user) {
                      $pq->where('manager_id', $user->id)
                        ->orWhereHas('teamMembers', function($tmq) use ($user) {
                            $tmq->where('users.id', $user->id);
                        });
                  });
            });
        } else {
            // Regular members only see tasks assigned to them
            $query->where('assigned_to', $user->id);
        }

        $projectId = $request->project_id;
        $project = $projectId ? Project::find($projectId) : null;

        $query->when($projectId, function($q) use ($projectId) {
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
                $query->whereDate('created_at', \Carbon\Carbon::today());
            } elseif ($dateFilter === 'yesterday') {
                $query->whereDate('created_at', \Carbon\Carbon::yesterday());
            } elseif ($dateFilter === 'this_week') {
                $query->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
            } elseif ($dateFilter === 'this_month') {
                $query->whereMonth('created_at', \Carbon\Carbon::now()->month)
                       ->whereYear('created_at', \Carbon\Carbon::now()->year);
            } elseif ($dateFilter === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [
                    \Carbon\Carbon::parse($request->start_date)->startOfDay(),
                    \Carbon\Carbon::parse($request->end_date)->endOfDay()
                ]);
            }
        }

        $tasks = $query->orderBy('order')->get();

        $columns = [
            'todo' => 'To Do',
            'in_progress' => 'In Progress',
            'review' => 'Done',
            'done' => 'Completed',
        ];

        $groupedTasks = $tasks->groupBy('status');

        if ($request->ajax()) {
            return view('teamportal::kanban.board', compact('groupedTasks', 'columns'))->render();
        }

        if ($user->isAdmin() || $user->isProjectManager()) {
            $projectsList = Project::orderBy('name');
            if (!$user->isAdmin()) {
                $projectsList->where(function($pq) use ($user) {
                    $pq->where('manager_id', $user->id)
                      ->orWhereHas('teamMembers', function($tmq) use ($user) {
                          $tmq->where('users.id', $user->id);
                      });
                });
            }
            $projects = $projectsList->get();
            if ($projects->isEmpty()) {
                $projects = Project::orderBy('name')->get();
            }
            $assignees = \App\Models\User::where('status', 'active')->orderBy('name')->get();
        } else {
            $projects = Project::whereHas('teamMembers', function($q) use ($user) {
                $q->where('users.id', $user->id);
            })->orderBy('name')->get();
            if ($projects->isEmpty()) {
                $projects = Project::orderBy('name')->get();
            }
            $assignees = collect([$user]);
        }

        return view('teamportal::kanban.index', compact('groupedTasks', 'columns', 'projects', 'project', 'assignees'));
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
                // Allow update if assigned to user OR user manages the project OR user is admin
                if ($task->assigned_to == auth()->id() || ($task->project && $task->project->manager_id == auth()->id()) || auth()->user()->isAdmin()) {
                    $newStatus = $taskData['status'];
                    if ($newStatus === 'done' && $task->status !== 'done' && !auth()->user()->isProjectManager() && !auth()->user()->isAdmin()) {
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
