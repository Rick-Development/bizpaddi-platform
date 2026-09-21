<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Task;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $tasks = Task::with('project')
            ->where('assigned_to', $user->id)
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(15);

        return view('teamportal::tasks.index', compact('tasks'));
    }

    public function show(Task $task)
    {
        $user = auth()->user();

        $task->load(['project', 'comments.user', 'media']);
        return view('teamportal::tasks.show', compact('task'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        $newStatus = $validated['status'];
        if ($newStatus === 'done' && $task->status !== 'done' && !$user->isProjectManager()) {
            return back()->with('error', 'Only Project Managers can mark tasks as Completed.');
        }

        $task->update(['status' => $newStatus]);

        return back()->with('success', 'Task status updated.');
    }

    public function destroy(Task $task)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && !$user->isProjectManager() && $task->assigned_to !== $user->id && $task->assigned_by !== $user->id) {
            return back()->with('error', 'You are not authorized to delete this task.');
        }

        $task->delete();

        return back()->with('success', 'Task deleted successfully.');
    }
}
