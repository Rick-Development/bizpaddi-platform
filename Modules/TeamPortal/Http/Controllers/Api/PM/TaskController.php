<?php

namespace Modules\TeamPortal\Http\Controllers\Api\PM;

use Illuminate\Http\Request;
use Modules\TeamPortal\Http\Controllers\Api\ApiController;
use Modules\ProjectManagement\Models\Task;
use Modules\ProjectManagement\Models\Project;
use App\Models\User;

class TaskController extends ApiController
{
    /**
     * GET /api/v1/team/pm/tasks/team-members
     * List all available team members for task assignment.
     */
    public function teamMembers(Request $request)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('Unauthorized access.', 403);
        }

        // Return all users (team members) with their basic info for the dropdown
        $members = User::select('id', 'name', 'email', 'role')->get();

        return $this->sendResponse($members, 'Team members retrieved successfully.');
    }

    /**
     * GET /api/v1/team/pm/tasks/options
     * Return available statuses and priorities for tasks.
     */
    public function options(Request $request)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('Unauthorized access.', 403);
        }

        return $this->sendResponse([
            'statuses' => [
                ['id' => 'todo', 'label' => 'To Do'],
                ['id' => 'in_progress', 'label' => 'In Progress'],
                ['id' => 'review', 'label' => 'Review'],
                ['id' => 'done', 'label' => 'Done'],
            ],
            'priorities' => [
                ['id' => 'low', 'label' => 'Low'],
                ['id' => 'medium', 'label' => 'Medium'],
                ['id' => 'high', 'label' => 'High'],
                ['id' => 'urgent', 'label' => 'Urgent'],
            ]
        ], 'Task options retrieved successfully.');
    }

    /**
     * GET /api/v1/team/pm/tasks
     * List all tasks across managed projects.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('Unauthorized access.', 403);
        }

        $managedProjectIds = Project::where('manager_id', $user->id)->pluck('id');

        $tasks = Task::whereIn('project_id', $managedProjectIds)
            ->with(['assignee', 'project'])
            ->latest()
            ->paginate(20);

        return $this->sendResponse($tasks, 'Tasks retrieved successfully.');
    }

    /**
     * POST /api/v1/team/pm/tasks
     * Create a new task in a managed project.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('Unauthorized access.', 403);
        }

        $request->validate([
            'project_id' => 'required|exists:pm_projects,id',
            'title' => 'required|string|max:255',
            'assigned_to' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $project = Project::where('manager_id', $user->id)->find($request->project_id);

        if (!$project) {
            return $this->sendError('Project not found or not managed by you.', 404);
        }

        $task = Task::create([
            'project_id' => $request->project_id,
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'assigned_by' => $user->id,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'status' => 'todo',
        ]);

        return $this->sendResponse($task, 'Task created successfully.', 201);
    }
    /**
     * GET /api/v1/team/pm/tasks/{id}
     * Get details of a specific task.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('Unauthorized access.', 403);
        }

        $managedProjectIds = Project::where('manager_id', $user->id)->pluck('id');

        $task = Task::whereIn('project_id', $managedProjectIds)
            ->with(['assignee', 'project', 'comments', 'timeLogs'])
            ->find($id);

        if (!$task) {
            return $this->sendError('Task not found.', 404);
        }

        return $this->sendResponse($task, 'Task retrieved successfully.');
    }

    /**
     * PUT/PATCH /api/v1/team/pm/tasks/{id}
     * Update a task.
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('Unauthorized access.', 403);
        }

        $managedProjectIds = Project::where('manager_id', $user->id)->pluck('id');

        $task = Task::whereIn('project_id', $managedProjectIds)->find($id);

        if (!$task) {
            return $this->sendError('Task not found.', 404);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'assigned_to' => 'sometimes|exists:users,id',
            'description' => 'nullable|string',
            'priority' => 'sometimes|in:low,medium,high',
            'due_date' => 'nullable|date',
            'status' => 'sometimes|string',
        ]);

        $task->update($request->only([
            'title',
            'description',
            'assigned_to',
            'priority',
            'due_date',
            'status'
        ]));

        return $this->sendResponse($task, 'Task updated successfully.');
    }

    /**
     * DELETE /api/v1/team/pm/tasks/{id}
     * Delete a task.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('Unauthorized access.', 403);
        }

        $managedProjectIds = Project::where('manager_id', $user->id)->pluck('id');

        $task = Task::whereIn('project_id', $managedProjectIds)->find($id);

        if (!$task) {
            return $this->sendError('Task not found.', 404);
        }

        $task->delete();

        return $this->sendResponse([], 'Task deleted successfully.');
    }
}
