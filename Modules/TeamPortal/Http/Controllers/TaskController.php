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

        $task->update(['status' => $validated['status']]);

        return back()->with('success', 'Task status updated.');
    }
}
