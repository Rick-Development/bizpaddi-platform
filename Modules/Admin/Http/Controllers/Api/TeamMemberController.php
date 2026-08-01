<?php

namespace Modules\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;

class TeamMemberController extends ApiController
{
    /**
     * GET /api/v1/admin/team-members
     * List all team members.
     */
    public function index(Request $request)
    {
        $members = User::latest()->paginate(20);
        return $this->sendResponse($members, 'Team members retrieved successfully.');
    }

    /**
     * POST /api/v1/admin/team-members
     * Add a new team member.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:8',
            'role'          => 'required|string',
            'status'        => 'nullable|string|in:active,inactive',
            'phone'         => 'nullable|string|max:20',
            'airtime_phone' => 'nullable|string|max:20',
            'data_phone'    => 'nullable|string|max:20',
            'department'    => 'nullable|string|max:255',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        if (!isset($validated['status'])) {
            $validated['status'] = 'active'; // Default status
        }

        $member = User::create($validated);

        return $this->sendResponse($member, 'Team member added successfully.', 201);
    }

    /**
     * GET /api/v1/admin/team-members/{id}
     * Get specific team member details.
     */
    public function show($id)
    {
        $member = User::with(['workLogs', 'attendance', 'assignedTasks'])->find($id);

        if (!$member) {
            return $this->sendError('Team member not found.', 404);
        }

        return $this->sendResponse($member, 'Team member details retrieved successfully.');
    }

    /**
     * PUT /api/v1/admin/team-members/{id}
     * Update team member.
     */
    public function update(Request $request, $id)
    {
        $member = User::find($id);

        if (!$member) {
            return $this->sendError('Team member not found.', 404);
        }

        $validated = $request->validate([
            'name'          => 'sometimes|string|max:255',
            'email'         => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($id)],
            'password'      => 'nullable|string|min:8',
            'role'          => 'sometimes|string',
            'status'        => 'nullable|string|in:active,inactive',
            'phone'         => 'nullable|string|max:20',
            'airtime_phone' => 'nullable|string|max:20',
            'data_phone'    => 'nullable|string|max:20',
            'department'    => 'nullable|string|max:255',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $member->update($validated);

        return $this->sendResponse($member->fresh(), 'Team member updated successfully.');
    }

    /**
     * DELETE /api/v1/admin/team-members/{id}
     * Remove team member.
     */
    public function destroy($id)
    {
        $member = User::find($id);

        if (!$member) {
            return $this->sendError('Team member not found.', 404);
        }

        $member->delete();

        return $this->sendResponse([], 'Team member deleted successfully.');
    }
}
