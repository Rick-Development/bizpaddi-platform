<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\TeamPortal\Models\BugReport;
use Modules\ProjectManagement\Models\Project;
use Modules\ProjectManagement\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class BugReportController extends Controller
{
    public function index(Request $request)
    {
        $query = BugReport::with(['project', 'reporter', 'assignee']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('steps_to_reproduce', 'like', '%' . $request->search . '%');
            });
        }

        // Stats for quick overview
        $stats = [
            'total'       => BugReport::count(),
            'open'        => BugReport::where('status', 'open')->count(),
            'in_progress' => BugReport::where('status', 'in_progress')->count(),
            'resolved'    => BugReport::where('status', 'resolved')->count(),
            'critical'    => BugReport::where('severity', 'critical')->whereNotIn('status', ['resolved', 'closed'])->count(),
        ];

        $bugs     = $query->latest()->paginate(20)->withQueryString();
        $projects = Project::orderBy('name')->get();

        return view('admin::bugs.index', compact('bugs', 'projects', 'stats'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        $tasks    = Task::whereIn('status', ['todo', 'in_progress', 'review'])->orderBy('title')->get();
        $users    = User::where('status', 'active')->orderBy('name')->get();

        return view('admin::bugs.create', compact('projects', 'tasks', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'required|string',
            'severity'            => 'required|in:low,medium,high,critical',
            'priority'            => 'required|in:low,medium,high,urgent',
            'status'              => 'required|in:open,in_progress,resolved,closed,wont_fix',
            'project_id'          => 'nullable|exists:pm_projects,id',
            'task_id'             => 'nullable|exists:pm_tasks,id',
            'assigned_to'         => 'nullable|exists:users,id',
            'due_date'            => 'nullable|date',
            'environment'         => 'nullable|string|max:100',
            'browser'             => 'nullable|string|max:100',
            'steps_to_reproduce'  => 'nullable|string',
            'expected_result'     => 'nullable|string',
            'actual_result'       => 'nullable|string',
            'resolution_notes'    => 'nullable|string',
            'screenshots'         => 'nullable|array',
            'screenshots.*'       => 'file|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $data = $validated;
        // If reported by is not set, set to first admin or null
        $data['reported_by'] = auth('admin')->id();

        if (!empty($validated['due_date'])) {
            $data['due_date'] = Carbon::parse($validated['due_date']);
        } else {
            $data['due_date'] = null;
        }

        if ($data['status'] === 'resolved') {
            $data['resolved_at'] = now();
        }

        $bug = BugReport::create($data);

        if ($request->hasFile('screenshots')) {
            foreach ($request->file('screenshots') as $file) {
                $bug->addMedia($file)->toMediaCollection('screenshots');
            }
        }

        return redirect()->route('admin.bugs.show', $bug)
            ->with('success', 'Bug report created successfully!');
    }

    public function show(BugReport $bug)
    {
        $bug->load(['project', 'task', 'reporter', 'assignee', 'media']);
        $users = User::where('status', 'active')->orderBy('name')->get();

        return view('admin::bugs.show', compact('bug', 'users'));
    }

    public function edit(BugReport $bug)
    {
        $projects = Project::orderBy('name')->get();
        $tasks    = Task::where('project_id', $bug->project_id)->orderBy('title')->get();
        $users    = User::where('status', 'active')->orderBy('name')->get();

        return view('admin::bugs.edit', compact('bug', 'projects', 'tasks', 'users'));
    }

    public function update(Request $request, BugReport $bug)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'required|string',
            'severity'            => 'required|in:low,medium,high,critical',
            'priority'            => 'required|in:low,medium,high,urgent',
            'status'              => 'required|in:open,in_progress,resolved,closed,wont_fix',
            'project_id'          => 'nullable|exists:pm_projects,id',
            'task_id'             => 'nullable|exists:pm_tasks,id',
            'assigned_to'         => 'nullable|exists:users,id',
            'due_date'            => 'nullable|date',
            'environment'         => 'nullable|string|max:100',
            'browser'             => 'nullable|string|max:100',
            'steps_to_reproduce'  => 'nullable|string',
            'expected_result'     => 'nullable|string',
            'actual_result'       => 'nullable|string',
            'resolution_notes'    => 'nullable|string',
            'screenshots'         => 'nullable|array',
            'screenshots.*'       => 'file|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        if (!empty($validated['due_date'])) {
            $validated['due_date'] = Carbon::parse($validated['due_date']);
        } else {
            $validated['due_date'] = null;
        }

        if ($validated['status'] === 'resolved' && $bug->status !== 'resolved') {
            $validated['resolved_at'] = now();
        } elseif ($validated['status'] !== 'resolved') {
            $validated['resolved_at'] = null;
        }

        $bug->update($validated);

        if ($request->hasFile('screenshots')) {
            foreach ($request->file('screenshots') as $file) {
                $bug->addMedia($file)->toMediaCollection('screenshots');
            }
        }

        return redirect()->route('admin.bugs.show', $bug)
            ->with('success', 'Bug report updated successfully!');
    }

    public function updateStatus(Request $request, BugReport $bug)
    {
        $validated = $request->validate([
            'status'           => 'required|in:open,in_progress,resolved,closed,wont_fix',
            'assigned_to'      => 'nullable|exists:users,id',
            'due_date'         => 'nullable|date',
            'resolution_notes' => 'nullable|string',
        ]);

        $updates = ['status' => $validated['status']];

        if ($request->has('assigned_to')) {
            $updates['assigned_to'] = $validated['assigned_to'];
        }
        if ($request->has('due_date')) {
            $updates['due_date'] = !empty($validated['due_date']) ? Carbon::parse($validated['due_date']) : null;
        }
        if ($request->has('resolution_notes')) {
            $updates['resolution_notes'] = $validated['resolution_notes'];
        }

        if ($validated['status'] === 'resolved' && $bug->status !== 'resolved') {
            $updates['resolved_at'] = now();
        } elseif ($validated['status'] !== 'resolved') {
            $updates['resolved_at'] = null;
        }

        $bug->update($updates);

        return redirect()->route('admin.bugs.show', $bug)
            ->with('success', 'Bug status updated to "' . $bug->status_label . '"!');
    }

    public function destroy(BugReport $bug)
    {
        $bug->clearMediaCollection('screenshots');
        $bug->delete();

        return redirect()->route('admin.bugs.index')->with('success', 'Bug report deleted successfully.');
    }
}
