<?php

namespace Modules\TeamPortal\Http\Controllers\QA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Task;
use Modules\ProjectManagement\Models\Project;
use Illuminate\Database\Eloquent\Builder;

class QaTestCaseController extends Controller
{
    /**
     * Projects the user is involved in (team member OR has tasks assigned)
     */
    private function myProjects(int $userId)
    {
        return Project::where(function (Builder $q) use ($userId) {
            $q->whereHas('teamMembers', fn($t) => $t->where('users.id', $userId))
              ->orWhereHas('tasks', fn($t) => $t->where('assigned_to', $userId));
        })->get();
    }

    /**
     * My assigned tasks for QA testing
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Task::with(['project', 'assignee'])
            ->where('assigned_to', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('project_id')) {
            $myProjectIds = $this->myProjects($user->id)->pluck('id');
            if ($myProjectIds->contains($request->project_id)) {
                $query->where('project_id', $request->project_id);
            }
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $tasks    = $query->latest()->paginate(15)->withQueryString();
        $projects = $this->myProjects($user->id);

        return view('teamportal::qa.test-cases.index', compact('tasks', 'projects'));
    }

    public function show(Task $task)
    {
        if ($task->assigned_to !== auth()->id() && !auth()->user()->isAdmin() && !auth()->user()->isProjectManager()) {
            abort(403);
        }
        $task->load(['project', 'comments.user', 'media']);
        return view('teamportal::qa.test-cases.show', compact('task'));
    }

    /**
     * QA tester can update task status (but not to "done" — that's PM)
     */
    public function updateStatus(Request $request, Task $task)
    {
        if ($task->assigned_to !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,review',
        ]);

        $task->update(['status' => $validated['status']]);

        return back()->with('success', 'Task status updated.');
    }
}
