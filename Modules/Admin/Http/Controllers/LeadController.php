<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Contact\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

        // Filter by date range (timeframe)
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $leads = $query->orderBy('updated_at', 'desc')->paginate(25);
        $teamMembers = User::all();

        return view('admin::leads.index', compact('leads', 'teamMembers'));
    }

    public function create()
    {
        $teamMembers = User::all();
        return view('admin::leads.create', compact('teamMembers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:leads,email',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'company_details' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'status' => 'required|in:new,contacted,nurturing,qualified,converted,lost',
            'score' => 'nullable|integer|min:0|max:100',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        if ($tenant = \App\Models\Tenant::resolveCurrent()) {
            $billingCheck = $tenant->chargeOrAuthorizeAction('lead_create', null, "Capture CRM Lead: {$validated['name']}");
            if (!$billingCheck['allowed']) {
                return redirect()->route('admin.billing.index')->with('error', $billingCheck['message']);
            }
        }

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
        $teamMembers = User::all();
        return view('admin::leads.edit', compact('lead', 'teamMembers'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:leads,email,' . $lead->id,
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'company_details' => 'nullable|string',
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

    /**
     * Export leads as CSV (respects current filters)
     */
    public function exportCsv(Request $request)
    {
        $query = Lead::with(['assignedTo']);

        if ($request->filled('status'))      $query->where('status', $request->status);
        if ($request->filled('assigned_to')) $query->where('assigned_to', $request->assigned_to);
        if ($request->filled('source'))      $query->where('source', $request->source);
        if ($request->filled('start_date'))  $query->whereDate('created_at', '>=', $request->start_date);
        if ($request->filled('end_date'))    $query->whereDate('created_at', '<=', $request->end_date);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name','like',"%{$s}%")->orWhere('email','like',"%{$s}%")->orWhere('company','like',"%{$s}%"));
        }

        $leads    = $query->latest()->get();
        $filename = 'leads_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($leads) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['#', 'Name', 'Company', 'Company Details', 'Email', 'Phone', 'Status', 'Score', 'Source', 'Assigned To', 'Value', 'Notes', 'Created At']);
            foreach ($leads as $i => $lead) {
                fputcsv($file, [
                    $lead->id,
                    $lead->name,
                    $lead->company ?? '',
                    str_replace(["\n", "\r"], ' ', $lead->company_details ?? ''),
                    $lead->email   ?? '',
                    $lead->phone   ?? '',
                    $lead->status,
                    $lead->score   ?? 0,
                    $lead->source  ?? '',
                    $lead->assignedTo->name ?? 'Unassigned',
                    $lead->value   ?? '',
                    str_replace(["\n", "\r"], ' ', $lead->notes ?? ''),
                    $lead->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export leads as PDF (respects current filters)
     */
    public function exportPdf(Request $request)
    {
        $query = Lead::with(['assignedTo']);

        if ($request->filled('status'))      $query->where('status', $request->status);
        if ($request->filled('assigned_to')) $query->where('assigned_to', $request->assigned_to);
        if ($request->filled('source'))      $query->where('source', $request->source);
        if ($request->filled('start_date'))  $query->whereDate('created_at', '>=', $request->start_date);
        if ($request->filled('end_date'))    $query->whereDate('created_at', '<=', $request->end_date);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name','like',"%{$s}%")->orWhere('email','like',"%{$s}%")->orWhere('company','like',"%{$s}%"));
        }

        $leads = $query->latest()->get();

        $settings = DB::table('settings')
            ->whereIn('key', ['watermark_logo', 'company_logo', 'site_name'])
            ->pluck('value', 'key');

        $watermarkLogo = isset($settings['watermark_logo']) && $settings['watermark_logo']
            ? 'data:image/jpeg;base64,' . base64_encode(Storage::get($settings['watermark_logo']))
            : null;
        $companyLogo   = isset($settings['company_logo']) && $settings['company_logo']
            ? 'data:image/jpeg;base64,' . base64_encode(Storage::get($settings['company_logo']))
            : null;
        $siteName = $settings['site_name'] ?? config('app.name');

        $pdf = Pdf::loadView('admin::leads.export-pdf', compact('leads', 'watermarkLogo', 'companyLogo', 'siteName'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('leads_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Import leads from a CSV file
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $header = fgetcsv($handle);

        if (!$header) {
            return back()->with('error', 'The CSV file is empty or invalid.');
        }

        // Normalise header keys: lowercase, spaces/dashes → underscores
        $header = array_map(fn($h) => strtolower(trim(str_replace([' ', '-'], '_', $h))), $header);

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 1) continue;

            $data = array_combine(
                array_slice($header, 0, count($row)),
                array_slice($row, 0, count($header))
            );

            $name = trim($data['name'] ?? $data['full_name'] ?? $data['lead_name'] ?? '');
            if (!$name) { $skipped++; continue; }

            $email = trim($data['email'] ?? $data['email_address'] ?? '');

            // Skip duplicate emails
            if ($email && Lead::where('email', $email)->exists()) {
                $skipped++;
                continue;
            }

            try {
                Lead::create([
                    'name'            => $name,
                    'company'         => trim($data['company'] ?? $data['company_name'] ?? ''),
                    'company_details' => trim($data['company_details'] ?? $data['details'] ?? $data['company_profile'] ?? $data['description'] ?? ''),
                    'email'           => $email ?: null,
                    'phone'           => trim($data['phone'] ?? $data['phone_number'] ?? $data['mobile'] ?? '') ?: null,
                    'source'          => trim($data['source'] ?? 'CSV Import'),
                    'status'          => in_array(($data['status'] ?? ''), ['new','contacted','nurturing','qualified','converted','lost'])
                                            ? $data['status'] : 'new',
                    'score'           => is_numeric($data['score'] ?? null) ? (int) $data['score'] : 0,
                    'notes'           => trim($data['notes'] ?? $data['note'] ?? ''),
                    'value'           => is_numeric($data['value'] ?? null) ? (float) $data['value'] : null,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row skipped: " . $e->getMessage();
                $skipped++;
            }
        }

        fclose($handle);

        $msg = "Import complete: {$imported} leads added, {$skipped} skipped.";
        if ($errors) $msg .= ' Errors: ' . implode('; ', array_slice($errors, 0, 3));

        return back()->with('success', $msg);
    }
}
