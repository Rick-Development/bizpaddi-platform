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

        if ($request->filled('due_filter')) {
            $dueFilter = $request->due_filter;
            $now = \Carbon\Carbon::now();
            $incompleteStatuses = function($q) {
                $q->whereNotIn('status', ['done', 'completed']);
            };

            if ($dueFilter === 'overdue') {
                $tasksQuery->where($incompleteStatuses)
                           ->whereNotNull('due_date')
                           ->where('due_date', '<', $now);
            } elseif ($dueFilter === 'due_soon') {
                $tasksQuery->where($incompleteStatuses)
                           ->whereNotNull('due_date')
                           ->whereBetween('due_date', [$now, $now->copy()->addHours(48)]);
            } elseif ($dueFilter === 'due_today') {
                $tasksQuery->where($incompleteStatuses)
                           ->whereNotNull('due_date')
                           ->whereDate('due_date', \Carbon\Carbon::today());
            } elseif ($dueFilter === 'due_this_week') {
                $tasksQuery->where($incompleteStatuses)
                           ->whereNotNull('due_date')
                           ->whereBetween('due_date', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
            } elseif ($dueFilter === 'has_due_date') {
                $tasksQuery->whereNotNull('due_date');
            } elseif ($dueFilter === 'no_due_date') {
                $tasksQuery->whereNull('due_date');
            }
        }

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

        // Completed Date Filter — filters tasks in the "done" column by when they were completed
        if ($request->filled('completed_filter')) {
            $completedFilter = $request->completed_filter;
            $now = \Carbon\Carbon::now();

            // Only apply to done tasks; use COALESCE for legacy records missing completed_at
            $completionCol = \Illuminate\Support\Facades\DB::raw(
                "COALESCE(completed_at, status_changed_at, updated_at)"
            );

            if ($completedFilter === 'completed_today') {
                $tasksQuery->where(function ($q) use ($now) {
                    $q->where('status', '!=', 'done')
                      ->orWhere(function ($sub) use ($now) {
                          $sub->where('status', 'done')
                              ->whereDate(\Illuminate\Support\Facades\DB::raw(
                                  "COALESCE(completed_at, status_changed_at, updated_at)"
                              ), $now->toDateString());
                      });
                });
            } elseif ($completedFilter === 'completed_yesterday') {
                $yesterday = $now->copy()->subDay()->toDateString();
                $tasksQuery->where(function ($q) use ($yesterday) {
                    $q->where('status', '!=', 'done')
                      ->orWhere(function ($sub) use ($yesterday) {
                          $sub->where('status', 'done')
                              ->whereDate(\Illuminate\Support\Facades\DB::raw(
                                  "COALESCE(completed_at, status_changed_at, updated_at)"
                              ), $yesterday);
                      });
                });
            } elseif ($completedFilter === 'completed_this_week') {
                $weekStart = $now->copy()->startOfWeek();
                $weekEnd   = $now->copy()->endOfWeek();
                $tasksQuery->where(function ($q) use ($weekStart, $weekEnd) {
                    $q->where('status', '!=', 'done')
                      ->orWhere(function ($sub) use ($weekStart, $weekEnd) {
                          $sub->where('status', 'done')
                              ->whereBetween(\Illuminate\Support\Facades\DB::raw(
                                  "COALESCE(completed_at, status_changed_at, updated_at)"
                              ), [$weekStart, $weekEnd]);
                      });
                });
            } elseif ($completedFilter === 'completed_this_month') {
                $monthStart = $now->copy()->startOfMonth();
                $monthEnd   = $now->copy()->endOfMonth();
                $tasksQuery->where(function ($q) use ($monthStart, $monthEnd) {
                    $q->where('status', '!=', 'done')
                      ->orWhere(function ($sub) use ($monthStart, $monthEnd) {
                          $sub->where('status', 'done')
                              ->whereBetween(\Illuminate\Support\Facades\DB::raw(
                                  "COALESCE(completed_at, status_changed_at, updated_at)"
                              ), [$monthStart, $monthEnd]);
                      });
                });
            } elseif (
                $completedFilter === 'completed_custom'
                && $request->filled('completed_start')
                && $request->filled('completed_end')
            ) {
                $cStart = \Carbon\Carbon::parse($request->completed_start)->startOfDay();
                $cEnd   = \Carbon\Carbon::parse($request->completed_end)->endOfDay();
                $tasksQuery->where(function ($q) use ($cStart, $cEnd) {
                    $q->where('status', '!=', 'done')
                      ->orWhere(function ($sub) use ($cStart, $cEnd) {
                          $sub->where('status', 'done')
                              ->whereBetween(\Illuminate\Support\Facades\DB::raw(
                                  "COALESCE(completed_at, status_changed_at, updated_at)"
                              ), [$cStart, $cEnd]);
                      });
                });
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
