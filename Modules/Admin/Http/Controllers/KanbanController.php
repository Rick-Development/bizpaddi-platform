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
        
        $tasks = Task::with(['assignee', 'project'])
            ->when($projectId, function($q) use ($projectId) {
                return $q->where('project_id', $projectId);
            })
            ->orderBy('order')
            ->get();

        $columns = [
            'todo' => 'To Do',
            'in_progress' => 'In Progress',
            'review' => 'Review',
            'done' => 'Done',
        ];

        $groupedTasks = $tasks->groupBy('status');
        
        $projects = Project::all();

        return view('admin::kanban.index', compact('groupedTasks', 'columns', 'projects', 'project'));
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
            Task::where('id', $taskData['id'])->update([
                'status' => $taskData['status'],
                'order' => $taskData['order'],
            ]);
        }

        return response()->json(['success' => true]);
    }
}
