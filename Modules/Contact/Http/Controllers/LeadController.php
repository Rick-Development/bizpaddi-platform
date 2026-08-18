<?php

namespace Modules\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Contact\Models\Lead;

class LeadController extends Controller
{
    /**
     * Show the multi-step form.
     */
    public function create()
    {
        $projectTypes = \Modules\Contact\Models\ProjectType::where('is_active', true)->orderBy('sort_order')->get();
        $budgets = \Modules\Contact\Models\BudgetRange::where('is_active', true)->orderBy('sort_order')->get();
        $timelines = \Modules\Contact\Models\ProjectTimeline::where('is_active', true)->orderBy('sort_order')->get();

        return view('contact::start-project', compact('projectTypes', 'budgets', 'timelines'));
    }

    /**
     * Store a new lead from the multi-step form.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'project_type' => 'nullable|string',
            'budget' => 'nullable|string',
            'timeline' => 'nullable|string',
            'description' => 'nullable|string',
            'utm_source' => 'nullable|string|max:255',
            'utm_medium' => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'utm_term' => 'nullable|string|max:255',
            'utm_content' => 'nullable|string|max:255',
        ]);

        $lead = Lead::where('email', $validated['email'])->first();

        if ($lead) {
            $existingMetadata = $lead->metadata ?? [];
            if (!is_array($existingMetadata)) {
                $existingMetadata = json_decode($existingMetadata, true) ?: [];
            }
            $history = $existingMetadata['submission_history'] ?? [];
            $history[] = [
                'project_type' => $validated['project_type'] ?? null,
                'budget' => $validated['budget'] ?? null,
                'timeline' => $validated['timeline'] ?? null,
                'notes' => $validated['description'] ?? null,
                'submitted_at' => now()->toIso8601String(),
                'utm_source' => $validated['utm_source'] ?? null,
                'utm_medium' => $validated['utm_medium'] ?? null,
                'utm_campaign' => $validated['utm_campaign'] ?? null,
            ];

            $lead->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? $lead->phone,
                'company' => $validated['company'] ?? $lead->company,
                'status' => 'new',
                'source' => 'Website Form (Resubmission)',
                'notes' => $validated['description'] ?? $lead->notes,
                'utm_source' => $validated['utm_source'] ?? $lead->utm_source,
                'utm_medium' => $validated['utm_medium'] ?? $lead->utm_medium,
                'utm_campaign' => $validated['utm_campaign'] ?? $lead->utm_campaign,
                'utm_term' => $validated['utm_term'] ?? $lead->utm_term,
                'utm_content' => $validated['utm_content'] ?? $lead->utm_content,
                'metadata' => array_merge($existingMetadata, [
                    'project_type' => $validated['project_type'] ?? null,
                    'budget' => $validated['budget'] ?? null,
                    'timeline' => $validated['timeline'] ?? null,
                    'submission_history' => $history,
                ])
            ]);
        } else {
            $lead = Lead::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'company' => $validated['company'] ?? null,
                'status' => 'new',
                'source' => 'Website Form',
                'notes' => $validated['description'] ?? null,
                'utm_source' => $validated['utm_source'] ?? null,
                'utm_medium' => $validated['utm_medium'] ?? null,
                'utm_campaign' => $validated['utm_campaign'] ?? null,
                'utm_term' => $validated['utm_term'] ?? null,
                'utm_content' => $validated['utm_content'] ?? null,
                'metadata' => [
                    'project_type' => $validated['project_type'] ?? null,
                    'budget' => $validated['budget'] ?? null,
                    'timeline' => $validated['timeline'] ?? null,
                ],
            ]);
        }

        try {
            // Send confirmation to the lead
            \Illuminate\Support\Facades\Mail::to($lead->email)->send(new \Modules\Contact\Emails\LeadConfirmationEmail($lead));
            
            // Send alert to admin
            \Illuminate\Support\Facades\Mail::to(config('mail.from.address', 'admin@rickdevelopment.com'))
                ->send(new \Modules\Contact\Emails\NewLeadAlertNotification($lead));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send lead emails: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Your project request has been submitted successfully!',
            'lead_id' => $lead->id
        ]);
    }
}
