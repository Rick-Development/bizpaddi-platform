<?php

namespace Modules\TeamPortal\Http\Controllers\Api\PM;

use Illuminate\Http\Request;
use Modules\TeamPortal\Http\Controllers\Api\ApiController;
use Modules\ProjectManagement\Models\Milestone;
use Modules\ProjectManagement\Models\Project;

class MilestoneController extends ApiController
{
    /**
     * GET /api/v1/team/pm/milestones
     * List all milestones across managed projects, or filter by project_id.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('Unauthorized access.', 403);
        }

        $managedProjectIds = Project::where('manager_id', $user->id)->pluck('id');

        $query = Milestone::whereIn('project_id', $managedProjectIds)->with(['project']);

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $milestones = $query->latest()->paginate(20);

        return $this->sendResponse($milestones, 'Milestones retrieved successfully.');
    }

    /**
     * POST /api/v1/team/pm/milestones
     * Create a new milestone.
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
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'nullable|in:pending,completed',
            'order' => 'nullable|integer',
        ]);

        $project = Project::where('manager_id', $user->id)->find($request->project_id);

        if (!$project) {
            return $this->sendError('Project not found or not managed by you.', 404);
        }

        $milestone = Milestone::create($request->only([
            'project_id', 'title', 'description', 'due_date', 'status', 'order'
        ]));

        return $this->sendResponse($milestone, 'Milestone created successfully.', 201);
    }

    /**
     * GET /api/v1/team/pm/milestones/{id}
     * Get a specific milestone.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('Unauthorized access.', 403);
        }

        $managedProjectIds = Project::where('manager_id', $user->id)->pluck('id');

        $milestone = Milestone::whereIn('project_id', $managedProjectIds)
            ->with(['project', 'tasks'])
            ->find($id);

        if (!$milestone) {
            return $this->sendError('Milestone not found.', 404);
        }

        return $this->sendResponse($milestone, 'Milestone retrieved successfully.');
    }

    /**
     * PUT/PATCH /api/v1/team/pm/milestones/{id}
     * Update a milestone.
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('Unauthorized access.', 403);
        }

        $managedProjectIds = Project::where('manager_id', $user->id)->pluck('id');

        $milestone = Milestone::whereIn('project_id', $managedProjectIds)->find($id);

        if (!$milestone) {
            return $this->sendError('Milestone not found.', 404);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'sometimes|in:pending,completed',
            'order' => 'nullable|integer',
        ]);

        $milestone->update($request->only([
            'title', 'description', 'due_date', 'status', 'order'
        ]));

        return $this->sendResponse($milestone, 'Milestone updated successfully.');
    }

    /**
     * DELETE /api/v1/team/pm/milestones/{id}
     * Delete a milestone.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('Unauthorized access.', 403);
        }

        $managedProjectIds = Project::where('manager_id', $user->id)->pluck('id');

        $milestone = Milestone::whereIn('project_id', $managedProjectIds)->find($id);

        if (!$milestone) {
            return $this->sendError('Milestone not found.', 404);
        }

        $milestone->delete();

        return $this->sendResponse([], 'Milestone deleted successfully.');
    }
}
