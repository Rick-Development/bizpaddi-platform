<?php

namespace Modules\Admin\Http\Controllers\Api\PM;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Api\ApiController;
use Modules\ProjectManagement\Models\Milestone;

class MilestoneController extends ApiController
{
    /**
     * GET /api/v1/admin/pm/milestones
     */
    public function index(Request $request)
    {
        $query = Milestone::with('project')->latest();

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $milestones = $query->paginate(20);
        return $this->sendResponse($milestones, 'Milestones retrieved successfully.');
    }

    /**
     * POST /api/v1/admin/pm/milestones
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id'  => 'required|exists:pm_projects,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
            'status'      => 'nullable|string|in:pending,completed',
            'order'       => 'nullable|integer|min:0',
        ]);

        if (!isset($validated['status'])) {
            $validated['status'] = 'pending';
        }

        $milestone = Milestone::create($validated);
        return $this->sendResponse($milestone->load('project'), 'Milestone created successfully.', 201);
    }

    /**
     * GET /api/v1/admin/pm/milestones/{id}
     */
    public function show($id)
    {
        $milestone = Milestone::with(['project', 'tasks'])->find($id);
        if (!$milestone) return $this->sendError('Milestone not found.', 404);
        return $this->sendResponse($milestone, 'Milestone details retrieved successfully.');
    }

    /**
     * PUT /api/v1/admin/pm/milestones/{id}
     */
    public function update(Request $request, $id)
    {
        $milestone = Milestone::find($id);
        if (!$milestone) return $this->sendError('Milestone not found.', 404);

        $validated = $request->validate([
            'project_id'  => 'sometimes|exists:pm_projects,id',
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
            'status'      => 'nullable|string|in:pending,completed',
            'order'       => 'nullable|integer|min:0',
        ]);

        $milestone->update($validated);
        return $this->sendResponse($milestone->fresh()->load('project'), 'Milestone updated successfully.');
    }

    /**
     * PATCH /api/v1/admin/pm/milestones/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        $milestone = Milestone::find($id);
        if (!$milestone) return $this->sendError('Milestone not found.', 404);

        $validated = $request->validate([
            'status' => 'required|string|in:pending,completed',
        ]);

        $milestone->update(['status' => $validated['status']]);

        return $this->sendResponse(
            ['id' => $milestone->id, 'status' => $milestone->fresh()->status],
            'Milestone status updated successfully.'
        );
    }

    /**
     * DELETE /api/v1/admin/pm/milestones/{id}
     */
    public function destroy($id)
    {
        $milestone = Milestone::find($id);
        if (!$milestone) return $this->sendError('Milestone not found.', 404);
        $milestone->delete();
        return $this->sendResponse([], 'Milestone deleted successfully.');
    }
}
