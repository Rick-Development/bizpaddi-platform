<?php

namespace Modules\Admin\Http\Controllers\Api\PM;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Admin\Http\Controllers\Api\ApiController;
use Modules\ProjectManagement\Models\Project;

class ProjectController extends ApiController
{
    /**
     * GET /api/v1/admin/pm/projects
     */
    public function index(Request $request)
    {
        $query = Project::with(['client', 'manager'])->latest();

        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('manager_id')) {
            $query->where('manager_id', $request->manager_id);
        }

        $projects = $query->paginate(20);
        return $this->sendResponse($projects, 'Projects retrieved successfully.');
    }

    /**
     * POST /api/v1/admin/pm/projects
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required_without:title|string|max:255',
            'title'       => 'required_without:name|string|max:255',
            'description' => 'nullable|string',
            'client_id'   => 'required|exists:clients,id',
            'manager_id'  => 'required|exists:users,id',
            'status'      => 'nullable|string|in:pending,active,on_hold,completed,cancelled',
            'start_date'  => 'nullable|date',
            'due_date'    => 'nullable|date|after_or_equal:start_date',
            'budget'      => 'nullable|numeric|min:0',
            'currency_id' => 'nullable|exists:currencies,id',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        // Support both 'title' and 'name'
        if (!isset($validated['name']) && isset($validated['title'])) {
            $validated['name'] = $validated['title'];
        }
        unset($validated['title']);

        if (!isset($validated['status'])) {
            $validated['status'] = 'pending';
        }

        $project = Project::create($validated);
        return $this->sendResponse($project->load(['client', 'manager']), 'Project created successfully.', 201);
    }

    /**
     * GET /api/v1/admin/pm/projects/{id}
     */
    public function show($id)
    {
        $project = Project::with(['client', 'manager', 'tasks', 'milestones', 'teamMembers'])->find($id);
        if (!$project) return $this->sendError('Project not found.', 404);
        return $this->sendResponse($project, 'Project details retrieved successfully.');
    }

    /**
     * PUT /api/v1/admin/pm/projects/{id}
     */
    public function update(Request $request, $id)
    {
        $project = Project::find($id);
        if (!$project) return $this->sendError('Project not found.', 404);

        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'client_id'   => 'sometimes|exists:clients,id',
            'manager_id'  => 'sometimes|exists:users,id',
            'status'      => 'nullable|string|in:pending,active,on_hold,completed,cancelled',
            'start_date'  => 'nullable|date',
            'due_date'    => 'nullable|date',
            'budget'      => 'nullable|numeric|min:0',
            'currency_id' => 'nullable|exists:currencies,id',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        if (isset($validated['title'])) {
            $validated['name'] = $validated['title'];
            unset($validated['title']);
        }

        $project->update($validated);
        return $this->sendResponse($project->fresh()->load(['client', 'manager']), 'Project updated successfully.');
    }

    /**
     * PATCH /api/v1/admin/pm/projects/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        $project = Project::find($id);
        if (!$project) return $this->sendError('Project not found.', 404);

        $validated = $request->validate([
            'status' => 'required|string|in:pending,active,on_hold,completed,cancelled',
        ]);

        $project->update(['status' => $validated['status']]);

        return $this->sendResponse(
            ['id' => $project->id, 'status' => $project->fresh()->status],
            'Project status updated successfully.'
        );
    }

    /**
     * DELETE /api/v1/admin/pm/projects/{id}
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        if (!$project) return $this->sendError('Project not found.', 404);
        $project->delete();
        return $this->sendResponse([], 'Project deleted successfully.');
    }
}
