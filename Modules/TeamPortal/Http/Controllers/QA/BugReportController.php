<?php

namespace Modules\TeamPortal\Http\Controllers\QA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\TeamPortal\Models\BugReport;
use Modules\ProjectManagement\Models\Project;
use Modules\ProjectManagement\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use Carbon\Carbon;

class BugReportController extends Controller
{
    /**
     * Check if user is an Administrator or Project Manager with full bug control.
     */
    private function isAdminOrPm($user): bool
    {
        return $user->isAdmin() || $user->isProjectManager();
    }

    /**
     * Returns accessible projects:
     * - Admins / PMs: all projects
     * - Others: only projects they are assigned to (as team member or task assignee).
     */
    private function myProjects($user)
    {
        if ($this->isAdminOrPm($user)) {
            return Project::orderBy('name')->get();
        }

        return Project::where(function (Builder $q) use ($user) {
            $q->whereHas('teamMembers', fn($t) => $t->where('users.id', $user->id))
              ->orWhereHas('tasks', fn($t) => $t->where('assigned_to', $user->id));
        })->orderBy('name')->get();
    }

    /**
     * Determine if user is allowed to view this bug report:
     * - Admins / PMs: any bug
     * - Reporter or Assignee: yes
     * - Team members on the project: yes
     */
    private function canViewBug(BugReport $bug, $user): bool
    {
        if ($this->isAdminOrPm($user)) {
            return true;
        }

        if ($bug->reported_by === $user->id || $bug->assigned_to === $user->id) {
            return true;
        }

        if ($bug->project_id) {
            $projectIds = $this->myProjects($user)->pluck('id');
            return $projectIds->contains($bug->project_id);
        }

        return false;
    }

    /**
     * Determine if user has full management rights (edit details, delete):
     * - Admins / PMs: yes
     * - The original reporter (e.g. QA tester who filed it): yes
     * - Developers / Designers / others: NO (view & update status alone).
     */
    private function canManageBug(BugReport $bug, $user): bool
    {
        if ($this->isAdminOrPm($user)) {
            return true;
        }

        return $bug->reported_by === $user->id;
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        $query = BugReport::with(['project', 'reporter', 'assignee']);

        if ($this->isAdminOrPm($user)) {
            // Admins & PMs have full access across all bug reports
        } elseif ($user->isQaTester()) {
            // QA testers see bugs they reported OR bugs in their assigned projects
            $myProjectIds = $this->myProjects($user)->pluck('id');
            $query->where(function (Builder $q) use ($user, $myProjectIds) {
                $q->where('reported_by', $user->id)
                  ->orWhereIn('project_id', $myProjectIds);
            });
        } else {
            // Developers, designers, etc. see bugs in projects they are on or bugs assigned to them
            $myProjectIds = $this->myProjects($user)->pluck('id');
            $query->where(function (Builder $q) use ($user, $myProjectIds) {
                $q->whereIn('project_id', $myProjectIds)
                  ->orWhere('assigned_to', $user->id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->filled('project_id')) {
            $myProjectIds = $this->myProjects($user)->pluck('id');
            if ($myProjectIds->contains($request->project_id)) {
                $query->where('project_id', $request->project_id);
            }
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $bugs     = $query->latest()->paginate(15)->withQueryString();
        $projects = $this->myProjects($user);

        return view('teamportal::qa.bugs.index', compact('bugs', 'projects'));
    }

    public function create()
    {
        $user = auth()->user();
        $projects = $this->myProjects($user);

        $tasksQuery = Task::whereIn('status', ['todo', 'in_progress', 'review']);
        if (!$this->isAdminOrPm($user)) {
            $tasksQuery->where('assigned_to', $user->id);
        }
        $tasks = $tasksQuery->get();
        $assignableUsers = User::where('status', 'active')->orderBy('name')->get();

        return view('teamportal::qa.bugs.create', compact('projects', 'tasks', 'assignableUsers'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $rules = [
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
        ];

        if ($this->isAdminOrPm($user)) {
            $rules['assigned_to'] = 'nullable|exists:users,id';
            $rules['due_date']    = 'nullable|date';
        }

        $validated = $request->validate($rules);

        $data = [
            ...$validated,
            'reported_by' => $user->id,
            'status'      => 'open',
        ];

        if ($this->isAdminOrPm($user) && $request->filled('assigned_to')) {
            $data['assigned_to'] = $request->input('assigned_to');
        }

        if ($this->isAdminOrPm($user) && $request->has('due_date')) {
            $data['due_date'] = !empty($request->due_date) ? Carbon::parse($request->due_date) : null;
        }

        $bug = BugReport::create($data);

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
        $user = auth()->user();

        if (!$this->canViewBug($bug, $user)) {
            abort(403, 'You do not have permission to view this bug report.');
        }

        $bug->load(['project', 'task', 'reporter', 'assignee', 'media']);
        $canManage = $this->canManageBug($bug, $user);
        $canAssign = $this->isAdminOrPm($user);
        $assignableUsers = User::where('status', 'active')->orderBy('name')->get();

        return view('teamportal::qa.bugs.show', compact('bug', 'canManage', 'canAssign', 'assignableUsers'));
    }

    /**
     * Dedicated status and assignment update endpoint for developers, designers, PMs, and team members.
     */
    public function updateStatus(Request $request, BugReport $bug)
    {
        $user = auth()->user();

        if (!$this->canViewBug($bug, $user)) {
            abort(403, 'You do not have permission to update this bug report.');
        }

        $canManage = $this->canManageBug($bug, $user);

        // Admins and reporters can set any status including closed/wont_fix.
        // Developers and designers can set open, in_progress, resolved.
        $allowedStatuses = $canManage
            ? 'open,in_progress,resolved,closed,wont_fix'
            : 'open,in_progress,resolved';

        $rules = [
            'status'           => 'required|in:' . $allowedStatuses,
            'resolution_notes' => 'nullable|string',
        ];

        // PMs and Admins are permitted to assign or reassign bugs and set deadline
        if ($this->isAdminOrPm($user)) {
            $rules['assigned_to'] = 'nullable|exists:users,id';
            $rules['due_date']    = 'nullable|date';
        }

        $validated = $request->validate($rules);

        $updates = ['status' => $validated['status']];

        if (array_key_exists('resolution_notes', $validated)) {
            $updates['resolution_notes'] = $validated['resolution_notes'];
        }

        if ($this->isAdminOrPm($user) && $request->has('assigned_to')) {
            $updates['assigned_to'] = $validated['assigned_to'] ?: null;
        }

        if ($this->isAdminOrPm($user) && $request->has('due_date')) {
            $updates['due_date'] = !empty($validated['due_date']) ? Carbon::parse($validated['due_date']) : null;
        }

        if ($validated['status'] === 'resolved' && $bug->status !== 'resolved') {
            $updates['resolved_at'] = now();
        } elseif ($validated['status'] !== 'resolved') {
            $updates['resolved_at'] = null;
        }

        $bug->update($updates);

        $msg = 'Bug status updated to "' . $bug->status_label . '" successfully!';
        if ($this->isAdminOrPm($user) && !empty($updates['assigned_to'])) {
            $assignee = User::find($updates['assigned_to']);
            if ($assignee) {
                $msg = 'Bug assigned to ' . $assignee->name . ' and status updated to "' . $bug->status_label . '"!';
            }
        }

        return redirect()->route('team.qa.bugs.show', $bug)
            ->with('success', $msg);
    }

    public function edit(BugReport $bug)
    {
        $user = auth()->user();

        if (!$this->canManageBug($bug, $user)) {
            abort(403, 'Only administrators and the reporter can edit bug details. You can update the bug status from the details view.');
        }

        $projects = $this->myProjects($user);
        $tasksQuery = Task::whereIn('status', ['todo', 'in_progress', 'review']);
        if (!$this->isAdminOrPm($user)) {
            $tasksQuery->where('assigned_to', $user->id);
        }
        $tasks = $tasksQuery->get();
        $assignableUsers = User::where('status', 'active')->orderBy('name')->get();

        return view('teamportal::qa.bugs.edit', compact('bug', 'projects', 'tasks', 'assignableUsers'));
    }

    public function update(Request $request, BugReport $bug)
    {
        $user = auth()->user();

        if (!$this->canManageBug($bug, $user)) {
            abort(403, 'Only administrators and the reporter can edit bug details.');
        }

        $rules = [
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
        ];

        if ($this->isAdminOrPm($user)) {
            $rules['assigned_to'] = 'nullable|exists:users,id';
            $rules['due_date']    = 'nullable|date';
        }

        $validated = $request->validate($rules);

        if ($this->isAdminOrPm($user) && $request->has('due_date')) {
            $validated['due_date'] = !empty($request->due_date) ? Carbon::parse($request->due_date) : null;
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

        return redirect()->route('team.qa.bugs.show', $bug)
            ->with('success', 'Bug report updated successfully!');
    }

    public function destroy(BugReport $bug)
    {
        $user = auth()->user();

        if (!$this->canManageBug($bug, $user)) {
            abort(403, 'Only administrators and the reporter can delete bug reports.');
        }

        $bug->clearMediaCollection('screenshots');
        $bug->delete();

        return redirect()->route('team.qa.bugs.index')->with('success', 'Bug report deleted.');
    }
}
