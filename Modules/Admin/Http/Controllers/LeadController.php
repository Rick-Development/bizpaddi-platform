<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Contact\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with(['assignedTo']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by assigned user
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Filter by source
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        // Filter by score range
        if ($request->filled('min_score')) {
            $query->where('score', '>=', $request->min_score);
        }
        if ($request->filled('max_score')) {
            $query->where('score', '<=', $request->max_score);
        }

        $leads = $query->latest()->paginate(25);
        $teamMembers = User::role(['admin', 'sales'])->get();

        return view('admin::leads.index', compact('leads', 'teamMembers'));
    }

    public function create()
    {
        $teamMembers = User::role(['admin', 'sales'])->get();
        return view('admin::leads.create', compact('teamMembers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:leads,email',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'status' => 'required|in:new,contacted,nurturing,qualified,converted,lost',
            'score' => 'nullable|integer|min:0|max:100',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $lead = Lead::create($validated);

        return redirect()->route('admin.leads.show', $lead)
            ->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        $lead->load(['assignedTo', 'convertedToContact']);
        return view('admin::leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $teamMembers = User::role(['admin', 'sales'])->get();
        return view('admin::leads.edit', compact('lead', 'teamMembers'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:leads,email,' . $lead->id,
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'status' => 'required|in:new,contacted,nurturing,qualified,converted,lost',
            'score' => 'nullable|integer|min:0|max:100',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $lead->update($validated);

        return redirect()->route('admin.leads.show', $lead)
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        if ($lead->status === 'converted') {
            return back()->with('error', 'Cannot delete a converted lead.');
        }

        $lead->delete();

        return redirect()->route('admin.leads.index')
            ->with('success', 'Lead deleted successfully.');
    }

    /**
     * Assign lead to a team member
     */
    public function assign(Request $request, Lead $lead)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $lead->update(['assigned_to' => $request->assigned_to]);

        return back()->with('success', 'Lead assigned successfully.');
    }

    /**
     * Update lead status
     */
    public function updateStatus(Request $request, Lead $lead)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,nurturing,qualified,converted,lost',
        ]);

        $lead->update(['status' => $request->status]);

        return back()->with('success', 'Lead status updated successfully.');
    }

    /**
     * Mark lead as contacted
     */
    public function markAsContacted(Lead $lead)
    {
        $lead->markAsContacted();

        return back()->with('success', 'Lead marked as contacted.');
    }

    /**
     * Convert lead to contact
     */
    public function convertToContact(Lead $lead)
    {
        if ($lead->status === 'converted') {
            return back()->with('error', 'Lead is already converted.');
        }

        try {
            $contact = $lead->convertToContact();

            return redirect()->route('admin.contacts.show', $contact)
                ->with('success', 'Lead converted to contact successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to convert lead: ' . $e->getMessage());
        }
    }
}
