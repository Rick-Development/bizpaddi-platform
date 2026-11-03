<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ProjectManagement\Models\Task;
use Modules\ProjectManagement\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::with(['project', 'assignee'])
            ->when($request->status ?? $request->due_filter, function ($query, $status) {
                if ($status === 'overdue') {
                    $query->whereNotIn('status', ['done', 'completed'])->whereNotNull('due_date')->where('due_date', '<', Carbon::now());
                } elseif ($status === 'due_soon') {
                    $query->whereNotIn('status', ['done', 'completed'])->whereNotNull('due_date')->whereBetween('due_date', [Carbon::now(), Carbon::now()->addHours(48)]);
                } elseif ($status === 'due_today') {
                    $query->whereNotIn('status', ['done', 'completed'])->whereNotNull('due_date')->whereDate('due_date', Carbon::today());
                } elseif ($status === 'due_this_week') {
                    $query->whereNotIn('status', ['done', 'completed'])->whereNotNull('due_date')->whereBetween('due_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                } elseif ($status === 'has_due_date') {
                    $query->whereNotNull('due_date');
                } elseif ($status === 'no_due_date') {
                    $query->whereNull('due_date');
                } else {
                    $query->where('status', $status);
                }
            })
            ->when($request->project_id, function ($query, $id) {
                $query->where('project_id', $id);
            })
            ->when($request->assigned_to, function ($query, $id) {
                $query->where('assigned_to', $id);
            })
            ->when($request->priority, function ($query, $priority) {
                $query->where('priority', $priority);
            })
            ->latest()
            ->paginate(20);

        $projects = Project::all();
        $users = User::all();

        return view('admin::tasks.index', compact('tasks', 'projects', 'users'));
    }

    public function create(Request $request)
    {
        $projects = Project::all();
        $users = User::all();
        $selectedProject = $request->project_id ? Project::with('milestones')->find($request->project_id) : null;
        $milestones = $selectedProject ? $selectedProject->milestones : collect();
        
        return view('admin::tasks.create', compact('projects', 'users', 'selectedProject', 'milestones'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:pm_projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:todo,in_progress,review,done,completed',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|string',
            'milestone_id' => 'nullable|exists:pm_milestones,id',
            'attachments.*' => 'nullable|file|max:20480',
        ]);

        // Parse datetime-local or plain date into a proper Carbon datetime
        if (!empty($validated['due_date'])) {
            $validated['due_date'] = Carbon::parse($validated['due_date']);
        } else {
            $validated['due_date'] = null;
        }

        if ($tenant = \App\Models\Tenant::resolveCurrent()) {
            $billingCheck = $tenant->chargeOrAuthorizeAction('task_create', null, "Create Task: {$validated['title']}");
            if (!$billingCheck['allowed']) {
                return redirect()->route('admin.billing.index')->with('error', $billingCheck['message']);
            }
        }

        $task = Task::create($validated);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $task->addMedia($file)->toMediaCollection('task_attachments');
            }
        }

        return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $task->load(['project', 'assignee', 'comments.user', 'media']);
        return view('admin::tasks.show', compact('task'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,review,done,completed',
        ]);

        $task->update(['status' => $validated['status']]);

        return back()->with('success', 'Task status updated to ' . ucfirst($validated['status'])); 
    }

    public function updateApproval(Request $request, Task $task)
    {
        $task->update([
            'requires_client_approval' => $request->has('requires_client_approval')
        ]);

        return back()->with('success', 'Approval requirement updated.');
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        $users = User::all();
        $task->load('project.milestones');
        $milestones = $task->project ? $task->project->milestones : collect();
        return view('admin::tasks.edit', compact('task', 'projects', 'users', 'milestones'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:pm_projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:todo,in_progress,review,done,completed',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|string',
            'milestone_id' => 'nullable|exists:pm_milestones,id',
            'attachments.*' => 'nullable|file|max:20480',
        ]);

        // Parse datetime-local or plain date into a proper Carbon datetime
        if (!empty($validated['due_date'])) {
            $validated['due_date'] = Carbon::parse($validated['due_date']);
        } else {
            $validated['due_date'] = null;
        }

        $task->update($validated);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $task->addMedia($file)->toMediaCollection('task_attachments');
            }
        }

        return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        if (in_array($task->status, ['review', 'done', 'completed'])) {
            return back()->with('error', 'Tasks in review or completed status cannot be deleted.');
        }

        $task->delete();
        return back()->with('success', 'Task deleted successfully.');
    }

    public function exportPdf(Request $request)
    {
        $query = Task::with(['project', 'assignee']);

        $projectFilter = null;
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
            $projectFilter = Project::find($request->project_id);
        }

        $userFilter = null;
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
            $userFilter = User::find($request->assigned_to);
        }

        if ($request->filled('status')) {
            if ($request->status === 'overdue') {
                $query->where('status', '!=', 'completed')->whereNotNull('due_date')->where('due_date', '<', Carbon::now());
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->latest()->get();

        // Calculate Overview KPIs
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->whereIn('status', ['completed', 'done'])->count();
        $inProgressTasks = $tasks->whereIn('status', ['in_progress', 'review'])->count();
        $todoTasks = $tasks->where('status', 'todo')->count();
        $overdueTasks = $tasks->filter(function($t) {
            return !in_array($t->status, ['completed', 'done']) && $t->due_date && $t->due_date->isPast();
        })->count();

        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;

        // Group by Project
        $groupedTasks = $tasks->groupBy(function($item) {
            return $item->project->title ?? 'General Tasks';
        });

        $pdf = Pdf::loadView('admin::tasks.export-pdf', compact(
            'tasks',
            'groupedTasks',
            'projectFilter',
            'userFilter',
            'totalTasks',
            'completedTasks',
            'inProgressTasks',
            'todoTasks',
            'overdueTasks',
            'completionRate'
        ));

        $pdf->setPaper('a4', 'portrait');

        $filename = 'tasks_report_' . Carbon::now()->format('Y_m_d_His') . '.pdf';

        if ($request->boolean('preview', false)) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    public function exportCsv(Request $request)
    {
        $query = Task::with(['project', 'assignee']);

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('status')) {
            if ($request->status === 'overdue') {
                $query->where('status', '!=', 'completed')->whereNotNull('due_date')->where('due_date', '<', Carbon::now());
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->latest()->get();

        $filename = 'tasks_export_' . Carbon::now()->format('Y_m_d_His') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($tasks) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Task Title', 'Project Name', 'Assigned To', 'Priority', 'Status', 'Due Date', 'Is Overdue', 'Created At']);

            foreach ($tasks as $task) {
                $isOverdue = !in_array($task->status, ['completed', 'done']) && $task->due_date && $task->due_date->isPast();

                fputcsv($file, [
                    $task->id,
                    $task->title,
                    $task->project->title ?? 'General',
                    $task->assignee->name ?? 'Unassigned',
                    strtoupper($task->priority ?? 'MEDIUM'),
                    ucfirst($task->status ?? 'todo'),
                    $task->due_date ? $task->due_date->format('Y-m-d') : 'No Due Date',
                    $isOverdue ? 'YES' : 'NO',
                    $task->created_at ? $task->created_at->format('Y-m-d H:i') : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
