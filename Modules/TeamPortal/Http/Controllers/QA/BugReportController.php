<?php

namespace Modules\TeamPortal\Http\Controllers\QA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\TeamPortal\Models\BugReport;
use Modules\ProjectManagement\Models\Project;
use Modules\ProjectManagement\Models\Task;

class BugReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = BugReport::with(['project', 'reporter', 'assignee'])
            ->where('reported_by', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $bugs    = $query->latest()->paginate(15)->withQueryString();
        $projects = Project::all();

        return view('teamportal::qa.bugs.index', compact('bugs', 'projects'));
    }

    public function create()
    {
        $user     = auth()->user();
        $projects = Project::all();
        $tasks    = Task::where('assigned_to', $user->id)->whereIn('status', ['todo', 'in_progress', 'review'])->get();
        return view('teamportal::qa.bugs.create', compact('projects', 'tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'required|string',
            'severity'            => 'required|in:low,medium,high,critical',
            'priority'            => 'required|in:low,medium,high,urgent',
            'project_id'          => 'nullable|exists:pm_projects,id',
            'task_id'             => 'nullable|exists:pm_tasks,id',
            'environment'         => 'nullable|string|max:100',
            'browser'             => 'nullable|string|max:100',
            'steps_to_reproduce'  => 'nullable|string',
            'expected_result'     => 'nullable|string',
            'actual_result'       => 'nullable|string',
            'screenshots'         => 'nullable|array',
            'screenshots.*'       => 'file|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $bug = BugReport::create([
            ...$validated,
            'reported_by' => auth()->id(),
            'status'      => 'open',
        ]);

        if ($request->hasFile('screenshots')) {
            foreach ($request->file('screenshots') as $file) {
                $bug->addMedia($file)->toMediaCollection('screenshots');
            }
        }

        return redirect()->route('team.qa.bugs.show', $bug)
            ->with('success', 'Bug report submitted successfully!');
    }

    public function show(BugReport $bug)
    {
        $this->authorizeAccess($bug);
        $bug->load(['project', 'task', 'reporter', 'assignee', 'media']);
        return view('teamportal::qa.bugs.show', compact('bug'));
    }

    public function edit(BugReport $bug)
    {
        $this->authorizeAccess($bug);
        $user     = auth()->user();
        $projects = Project::all();
        $tasks    = Task::where('assigned_to', $user->id)->get();
        return view('teamportal::qa.bugs.edit', compact('bug', 'projects', 'tasks'));
    }

    public function update(Request $request, BugReport $bug)
    {
        $this->authorizeAccess($bug);

        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'required|string',
            'severity'            => 'required|in:low,medium,high,critical',
            'priority'            => 'required|in:low,medium,high,urgent',
            'status'              => 'required|in:open,in_progress,resolved,closed,wont_fix',
            'project_id'          => 'nullable|exists:pm_projects,id',
            'task_id'             => 'nullable|exists:pm_tasks,id',
            'environment'         => 'nullable|string|max:100',
            'browser'             => 'nullable|string|max:100',
            'steps_to_reproduce'  => 'nullable|string',
            'expected_result'     => 'nullable|string',
            'actual_result'       => 'nullable|string',
            'resolution_notes'    => 'nullable|string',
            'screenshots'         => 'nullable|array',
            'screenshots.*'       => 'file|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        if ($validated['status'] === 'resolved' && $bug->status !== 'resolved') {
            $validated['resolved_at'] = now();
        }

        $bug->update($validated);

        if ($request->hasFile('screenshots')) {
            foreach ($request->file('screenshots') as $file) {
                $bug->addMedia($file)->toMediaCollection('screenshots');
            }
        }

        return redirect()->route('team.qa.bugs.show', $bug)
            ->with('success', 'Bug report updated successfully!');
    }

    public function destroy(BugReport $bug)
    {
        $this->authorizeAccess($bug);
        $bug->clearMediaCollection('screenshots');
        $bug->delete();
        return redirect()->route('team.qa.bugs.index')->with('success', 'Bug report deleted.');
    }

    private function authorizeAccess(BugReport $bug): void
    {
        if ($bug->reported_by !== auth()->id() && !auth()->user()->isAdmin() && !auth()->user()->isProjectManager()) {
            abort(403, 'You do not have permission to access this bug report.');
        }
    }
}
