<?php

namespace Modules\ClientPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Task;

class TaskController extends Controller
{
    public function approve(Request $request, $id)
    {
        $client = auth()->guard('client')->user();
        $task = Task::whereHas('project', function ($query) use ($client) {
            $query->where('client_id', $client->id);
        })->findOrFail($id);

        $task->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Task approved successfully.');
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'client_feedback' => 'required|string|max:1000',
        ]);

        $client = auth()->guard('client')->user();
        $task = Task::whereHas('project', function ($query) use ($client) {
            $query->where('client_id', $client->id);
        })->findOrFail($id);

        $task->update([
            'approval_status' => 'changes_requested',
            'client_feedback' => $validated['client_feedback'],
        ]);

        return back()->with('info', 'Changes have been requested for this task.');
    }
}
