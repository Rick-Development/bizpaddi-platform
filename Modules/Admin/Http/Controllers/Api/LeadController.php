<?php

namespace Modules\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Contact\Models\Lead;

class LeadController extends ApiController
{
    /**
     * GET /api/v1/admin/leads
     */
    public function index(Request $request)
    {
        $leads = Lead::with('assignedTo')->latest()->paginate(20);
        return $this->sendResponse($leads, 'Leads retrieved successfully.');
    }

    /**
     * GET /api/v1/admin/leads/{id}
     */
    public function show($id)
    {
        $lead = Lead::with(['assignedTo', 'convertedToContact'])->find($id);
        if (!$lead) return $this->sendError('Lead not found.', 404);
        return $this->sendResponse($lead, 'Lead details retrieved successfully.');
    }

    /**
     * POST /api/v1/admin/leads
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'status' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'score' => 'nullable|integer|min:0|max:100',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'metadata' => 'nullable|array',
        ]);

        if (!isset($validated['status'])) {
            $validated['status'] = 'new';
        }

        $lead = Lead::create($validated);
        
        return $this->sendResponse($lead, 'Lead created successfully.', 201);
    }

    /**
     * PUT /api/v1/admin/leads/{id}
     */
    public function update(Request $request, $id)
    {
        $lead = Lead::find($id);
        if (!$lead) return $this->sendError('Lead not found.', 404);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'status' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'score' => 'nullable|integer|min:0|max:100',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'metadata' => 'nullable|array',
        ]);

        $lead->update($validated);
        
        return $this->sendResponse($lead->fresh(), 'Lead updated successfully.');
    }

    /**
     * DELETE /api/v1/admin/leads/{id}
     */
    public function destroy($id)
    {
        $lead = Lead::find($id);
        if (!$lead) return $this->sendError('Lead not found.', 404);
        
        $lead->delete();
        return $this->sendResponse([], 'Lead deleted successfully.');
    }
}
