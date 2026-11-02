<?php

namespace Modules\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Contact\Models\Lead;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class FintechLandingPageController extends Controller
{
    /**
     * Display the fintech B2B landing page.
     */
    public function index()
    {
        return view('contact::fintech');
    }

    /**
     * Store a lead submission from the fintech B2B form.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'solution_interest' => 'required|string|in:Payment Platform,Fintech Application,Financial Infrastructure,Crypto / Stablecoin Product,Digital Wallet,Banking / MFB Platform,Other',
            'project_stage' => 'required|string|in:Idea / Concept,MVP,Existing Product,Scaling Existing Platform,Enterprise Transformation',
            'description' => 'nullable|string',
            'utm_source' => 'nullable|string|max:255',
            'utm_medium' => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'utm_term' => 'nullable|string|max:255',
            'utm_content' => 'nullable|string|max:255',
            'clicked_cta' => 'nullable|string|max:255',
        ]);

        $lead = Lead::where('email', $validated['email'])->first();

        if ($lead) {
            $existingMetadata = $lead->metadata ?? [];
            if (!is_array($existingMetadata)) {
                $existingMetadata = json_decode($existingMetadata, true) ?: [];
            }
            $history = $existingMetadata['submission_history'] ?? [];
            $history[] = [
                'landing_page' => 'fintech',
                'job_title' => $validated['job_title'] ?? null,
                'solution_interest' => $validated['solution_interest'],
                'project_stage' => $validated['project_stage'],
                'notes' => $validated['description'] ?? null,
                'clicked_cta' => $validated['clicked_cta'] ?? 'unknown_cta',
                'submitted_at' => now()->toIso8601String(),
                'utm_source' => $validated['utm_source'] ?? null,
                'utm_medium' => $validated['utm_medium'] ?? null,
                'utm_campaign' => $validated['utm_campaign'] ?? null,
                'utm_content' => $validated['utm_content'] ?? null,
                'utm_term' => $validated['utm_term'] ?? null,
            ];

            $lead->update([
                'name' => $validated['name'],
                'company' => $validated['company'] ?? $lead->company,
                'status' => 'new',
                'source' => 'Fintech Landing Page (Resubmission)',
                'notes' => $validated['description'] ?? $lead->notes,
                'utm_source' => $validated['utm_source'] ?? $lead->utm_source,
                'utm_medium' => $validated['utm_medium'] ?? $lead->utm_medium,
                'utm_campaign' => $validated['utm_campaign'] ?? $lead->utm_campaign,
                'utm_term' => $validated['utm_term'] ?? $lead->utm_term,
                'utm_content' => $validated['utm_content'] ?? $lead->utm_content,
                'metadata' => array_merge($existingMetadata, [
                    'landing_page' => 'fintech',
                    'job_title' => $validated['job_title'] ?? null,
                    'solution_interest' => $validated['solution_interest'],
                    'project_stage' => $validated['project_stage'],
                    'clicked_cta' => $validated['clicked_cta'] ?? 'unknown_cta',
                    'submitted_at' => now()->toIso8601String(),
                    'submission_history' => $history,
                ])
            ]);
        } else {
            $lead = Lead::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'company' => $validated['company'] ?? null,
                'status' => 'new',
                'source' => 'Fintech Landing Page',
                'notes' => $validated['description'] ?? null,
                'utm_source' => $validated['utm_source'] ?? null,
                'utm_medium' => $validated['utm_medium'] ?? null,
                'utm_campaign' => $validated['utm_campaign'] ?? null,
                'utm_term' => $validated['utm_term'] ?? null,
                'utm_content' => $validated['utm_content'] ?? null,
                'metadata' => [
                    'landing_page' => 'fintech',
                    'job_title' => $validated['job_title'] ?? null,
                    'solution_interest' => $validated['solution_interest'],
                    'project_stage' => $validated['project_stage'],
                    'clicked_cta' => $validated['clicked_cta'] ?? 'unknown_cta',
                    'submitted_at' => now()->toIso8601String(),
                ]
            ]);
        }

        try {
            // Send confirmation to the lead
            Mail::to($lead->email)->send(new \Modules\Contact\Emails\LeadConfirmationEmail($lead));
            
            // Send alert to admin
            Mail::to(config('mail.from.address', 'admin@bizpaddi.com'))
                ->send(new \Modules\Contact\Emails\NewLeadAlertNotification($lead));
        } catch (\Exception $e) {
            Log::error('Failed to send fintech lead emails: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Your consultation request has been submitted successfully!',
            'lead_id' => $lead->id
        ]);
    }
}
