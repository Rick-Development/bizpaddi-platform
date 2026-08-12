<?php

namespace Modules\TeamPortal\Http\Controllers\BDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Contact\Models\Lead;

class LeadController extends Controller
{
    public function index()
    {
        $user = auth('web')->user();
        $leads = Lead::where('assigned_to', $user->id)->orderBy('created_at', 'desc')->paginate(20);
        return view('teamportal::bdm.leads.index', compact('leads'));
    }

    public function create()
    {
        $sources = \Modules\Contact\Models\LeadSource::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        return view('teamportal::bdm.leads.create', compact('sources'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'nullable|email|max:255|unique:leads,email',
            'phone'              => 'nullable|string|max:30',
            'company'            => 'nullable|string|max:255',
            'source'             => 'nullable|string|max:100',
            'status'             => 'required|in:new,contacted,nurturing,qualified,converted,lost',
            'score'              => 'nullable|integer|min:0|max:100',
            'value'              => 'nullable|numeric|min:0',
            'expected_close_date'=> 'nullable|date',
            'notes'              => 'nullable|string',
        ]);

        $validated['assigned_to'] = auth('web')->id();

        Lead::create($validated);

        return redirect()->route('team.bdm.leads.index')
            ->with('success', 'Lead recorded successfully.');
    }

    public function show(Lead $lead)
    {
        $this->authoriseLead($lead);
        return view('teamportal::bdm.leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $this->authoriseLead($lead);
        return view('teamportal::bdm.leads.edit', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $this->authoriseLead($lead);

        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'nullable|email|max:255|unique:leads,email,' . $lead->id,
            'phone'              => 'nullable|string|max:30',
            'company'            => 'nullable|string|max:255',
            'source'             => 'nullable|string|max:100',
            'status'             => 'required|in:new,contacted,nurturing,qualified,converted,lost',
            'score'              => 'nullable|integer|min:0|max:100',
            'value'              => 'nullable|numeric|min:0',
            'expected_close_date'=> 'nullable|date',
            'notes'              => 'nullable|string',
        ]);

        if ($validated['status'] === 'converted' && !$lead->converted_at) {
            $validated['converted_at'] = now();
        }

        $lead->update($validated);

        return redirect()->route('team.bdm.leads.show', $lead)
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $this->authoriseLead($lead);
        $lead->delete();
        return redirect()->route('team.bdm.leads.index')
            ->with('success', 'Lead deleted.');
    }

    private function authoriseLead(Lead $lead): void
    {
        if ($lead->assigned_to !== auth('web')->id()) {
            abort(403);
        }
    }

    public function kanban()
    {
        $user = auth('web')->user();
        
        // Group leads by status for the Kanban board
        $leads = Lead::where('assigned_to', $user->id)
                    ->orderBy('updated_at', 'desc')
                    ->get()
                    ->groupBy('status');

        $stages = ['new', 'contacted', 'nurturing', 'qualified', 'converted', 'lost'];
        
        return view('teamportal::bdm.leads.kanban', compact('leads', 'stages'));
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,nurturing,qualified,converted,lost',
            'value' => 'nullable|numeric',
            'expected_close_date' => 'nullable|date',
        ]);

        $user = auth('web')->user();
        if ($lead->assigned_to !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $lead->status = $request->status;
        if ($request->has('value')) {
            $lead->value = $request->value;
        }
        if ($request->has('expected_close_date')) {
            $lead->expected_close_date = $request->expected_close_date;
        }

        // If newly converted, record date
        if ($request->status === 'converted' && !$lead->converted_at) {
            $lead->converted_at = now();
        }

        $lead->save();

        return response()->json(['success' => true, 'message' => 'Lead updated successfully.']);
    }

    public function logActivity(Request $request, Lead $lead)
    {
        $request->validate([
            'note' => 'required|string',
            'contacted' => 'boolean'
        ]);

        $user = auth('web')->user();
        if ($lead->assigned_to !== $user->id) {
            return back()->with('error', 'Unauthorized');
        }

        // Append to notes (could be JSON or simple text, we'll append text with date)
        $timestamp = now()->format('M d, Y H:i');
        $newNote = "[{$timestamp}] {$user->name}: {$request->note}";
        $lead->notes = $lead->notes ? $lead->notes . "\n" . $newNote : $newNote;

        if ($request->contacted) {
            $lead->last_contacted_at = now();
            if ($lead->status === 'new') {
                $lead->status = 'contacted';
            }
        }

        $lead->save();

        return back()->with('success', 'Activity logged successfully.');
    }
}
