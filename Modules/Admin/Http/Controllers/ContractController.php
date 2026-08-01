<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Contracts\Models\Contract;
use Modules\Contracts\Models\ContractTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::with(['creator', 'currency']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('contract_type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('contract_number', 'like', '%' . $request->search . '%')
                  ->orWhere('title', 'like', '%' . $request->search . '%')
                  ->orWhere('party_name', 'like', '%' . $request->search . '%');
            });
        }

        $contracts = $query->latest()->paginate(20);
        $expiringSoon = Contract::expiringSoon()->count();

        return view('admin::contracts.index', compact('contracts', 'expiringSoon'));
    }

    public function create(Request $request)
    {
        $templates = ContractTemplate::active()->get();
        $clients = \Modules\Clients\Models\Client::where('is_active', true)->orderBy('name')->get();
        $currencies = \Modules\Accounting\Models\Currency::active()->get();

        $order = null;
        if ($request->has('order_id')) {
            $order = \Modules\ClientPortal\Models\ServiceOrder::with(['client', 'service', 'quote', 'invoice'])->find($request->order_id);
        }

        return view('admin::contracts.create', compact('templates', 'clients', 'currencies', 'order'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'contract_type' => 'required|in:sla,nda,employment,service_agreement,partnership,vendor,other',
            'party_type' => 'nullable|in:client,employee,vendor,partner,other',
            'party_id' => 'nullable|integer',
            'party_name' => 'required|string|max:255',
            'party_email' => 'nullable|email',
            'party_address' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration_months' => 'nullable|integer|min:0',
            'duration_weeks' => 'nullable|integer|min:0|max:52',
            'auto_renew' => 'boolean',
            'value' => 'nullable|numeric|min:0',
            'currency_id' => 'nullable|exists:currencies,id',
            'content' => 'required|string',
            'terms' => 'nullable|string',
            'notes' => 'nullable|string',
            'order_id' => 'nullable|exists:service_orders,id',
        ]);

        $validated['created_by'] = auth('admin')->id();
        $validated['status'] = 'draft';

        $contract = Contract::create($validated);
        
        // Replace template variables with actual data
        $contract->content = $contract->replaceVariables($contract->content);
        if ($contract->terms) {
            $contract->terms = $contract->replaceVariables($contract->terms);
        }
        $contract->save();

        // Create initial version
        $contract->createVersion();

        // Link ServiceOrder if present
        if ($request->filled('order_id')) {
            $order = \Modules\ClientPortal\Models\ServiceOrder::find($request->order_id);
            if ($order) {
                $order->update([
                    'contract_id' => $contract->id,
                    'status' => 'contracted', // Updated to match ServiceOrder validation
                ]);
            }
        }

        return redirect()->route('admin.contracts.show', $contract)
            ->with('success', 'Contract created successfully.');
    }

    public function show(Contract $contract)
    {
        $contract->load(['creator', 'approver', 'currency', 'attachments.uploader', 'versions.creator']);
        return view('admin::contracts.show', compact('contract'));
    }

    public function edit(Contract $contract)
    {
        $clients = \Modules\Clients\Models\Client::where('is_active', true)->orderBy('name')->get();
        $currencies = \Modules\Accounting\Models\Currency::active()->get();

        return view('admin::contracts.edit', compact('contract', 'clients', 'currencies'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'party_name' => 'required|string|max:255',
            'party_email' => 'nullable|email',
            'party_address' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration_months' => 'nullable|integer|min:0',
            'duration_weeks' => 'nullable|integer|min:0|max:52',
            'auto_renew' => 'boolean',
            'value' => 'nullable|numeric|min:0',
            'currency_id' => 'nullable|exists:currencies,id',
            'content' => 'required|string',
            'terms' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $contract->update($validated);
        
        // Replace template variables with actual data
        $contract->content = $contract->replaceVariables($contract->content);
        if ($contract->terms) {
            $contract->terms = $contract->replaceVariables($contract->terms);
        }
        $contract->save();

        return redirect()->route('admin.contracts.show', $contract)
            ->with('success', 'Contract updated successfully.');
    }

    public function destroy(Contract $contract)
    {
        // Delete attachments from storage
        foreach ($contract->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        // Delete signature if exists
        if ($contract->signature_file) {
            Storage::disk('public')->delete($contract->signature_file);
        }

        $contract->delete();

        return redirect()->route('admin.contracts.index')
            ->with('success', 'Contract deleted successfully.');
    }

    public function downloadPDF(Contract $contract)
    {
        $contract->load(['currency', 'attachments']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin::contracts.pdf', compact('contract'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('Contract-' . $contract->contract_number . '.pdf');
    }

    public function downloadDocx(Contract $contract)
    {
        $contract->load(['currency']);
        $settings = \Modules\Settings\Models\Setting::all()->pluck('value', 'key');
        
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();

        // Watermark
        if(isset($settings['watermark_logo']) && Storage::disk('public')->exists($settings['watermark_logo'])) {
             $header = $section->addHeader();
             $header->addWatermark(
                 storage_path('app/public/' . $settings['watermark_logo']),
                 [
                     'marginTop' => 200, 
                     'marginLeft' => 50,
                     'opacity' => 40, // Increased opacity for visibility
                     'width' => 400,  // Explicit width to ensure it's not tiny
                     'positioning' => 'absolute',
                 ]
             );
        }

        // Header style
        $headerStyle = ['size' => 16, 'bold' => true];
        $subHeaderStyle = ['size' => 12, 'bold' => true, 'color' => '666666'];
        $normalStyle = ['size' => 10];
        $boldStyle = ['size' => 10, 'bold' => true];

        // Logo
        if(isset($settings['company_logo']) && Storage::disk('public')->exists($settings['company_logo'])) {
            $section->addImage(
                storage_path('app/public/' . $settings['company_logo']),
                ['height' => 60, 'align' => 'left']
            );
            $section->addTextBreak(1);
        } else {
             $section->addText($settings['site_name'] ?? config('app.name'), $headerStyle);
        }

        // Title
        $section->addText($contract->title, $headerStyle, ['alignment' => 'center']);
        $section->addText('Contract #: ' . $contract->contract_number, $subHeaderStyle, ['alignment' => 'center']);
        $section->addTextBreak(1);

        // Parties
        $table = $section->addTable();
        $table->addRow();
        
        // First Party
        $cell1 = $table->addCell(4500);
        $cell1->addText('First Party (Provider)', $subHeaderStyle);
        $cell1->addText($settings['site_name'] ?? config('app.name'), $boldStyle);
        $cell1->addText($settings['contact_address'] ?? '', $normalStyle);
        if(isset($settings['contact_email'])) $cell1->addText($settings['contact_email'], $normalStyle);

        // Second Party
        $cell2 = $table->addCell(4500);
        $cell2->addText('Second Party (' . ucfirst($contract->party_type) . ')', $subHeaderStyle);
        $cell2->addText($contract->party_name, $boldStyle);
        $cell2->addText($contract->party_address ?? '', $normalStyle);
        if($contract->party_email) $cell2->addText($contract->party_email, $normalStyle);
        
        $section->addTextBreak(1);

        // Details
        $section->addText('Contract Details:', $subHeaderStyle);
        $section->addText('Start Date: ' . $contract->start_date->format('M d, Y'), $normalStyle);
        
        // Duration with both months and weeks
        $durationParts = [];
        if ($contract->duration_months) {
            $durationParts[] = $contract->duration_months . ' month' . ($contract->duration_months > 1 ? 's' : '');
        }
        if ($contract->duration_weeks) {
            $durationParts[] = $contract->duration_weeks . ' week' . ($contract->duration_weeks > 1 ? 's' : '');
        }
        $durationString = !empty($durationParts) ? implode(' ', $durationParts) : 'N/A';
        $section->addText('Duration: ' . $durationString, $normalStyle);
        
        if($contract->value) {
            $symbol = $contract->currency->symbol ?? '$';
            $section->addText('Value: ' . $symbol . number_format($contract->value, 2), $normalStyle);
        }
        $section->addTextBreak(1);

        // Content
        $section->addText('Agreement', $subHeaderStyle);
        $lines = explode("\n", $contract->content);
        foreach($lines as $line) {
            $section->addText(trim($line), $normalStyle);
        }
        $section->addTextBreak(1);

        // Terms
        if($contract->terms) {
             $section->addText('Terms & Conditions', $subHeaderStyle);
             $lines = explode("\n", $contract->terms);
             foreach($lines as $line) {
                 $section->addText(trim($line), $normalStyle);
             }
             $section->addTextBreak(2);
        }

        // Signatures
        $signTable = $section->addTable();
        $signTable->addRow();

        // Company Signature
        $signCell1 = $signTable->addCell(4500);
        $signCell1->addText('Signed for ' . ($settings['site_name'] ?? config('app.name')) . ':', $boldStyle);
        
        if(isset($settings['company_signature']) && Storage::disk('public')->exists($settings['company_signature'])) {
             $signCell1->addTextBreak(1);
             $signCell1->addImage(
                storage_path('app/public/' . $settings['company_signature']),
                ['height' => 40, 'align' => 'left']
            );
        } else {
            $signCell1->addTextBreak(3);
        }

        $repName = $settings['company_representative_name'] ?? 'Authorized Representative';
        $signCell1->addText('Name: ' . $repName, $normalStyle);
        $signCell1->addText('Date: ' . date('M d, Y'), $normalStyle);

        // Client Signature
        $signCell2 = $signTable->addCell(4500);
        $signCell2->addText('Signed for ' . $contract->party_name . ':', $boldStyle);
        $signCell2->addTextBreak(3);
        $signCell2->addText('Name: ' . $contract->party_name, $normalStyle);
        $signCell2->addText('Date: ______________________', $normalStyle);

        // Save
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        
        $fileName = 'Contract-' . $contract->contract_number . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $objWriter->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function uploadSignature(Request $request, Contract $contract)
    {
        $request->validate([
            'signature' => 'required|image|max:2048',
            'signed_by' => 'required|string|max:255',
        ]);

        // Delete old signature if exists
        if ($contract->signature_file) {
            Storage::disk('public')->delete($contract->signature_file);
        }

        $path = $request->file('signature')->store('contracts/signatures', 'public');

        $contract->update([
            'signature_file' => $path,
            'signed_by' => $request->signed_by,
            'signed_at' => now(),
            'status' => 'active',
        ]);

        return back()->with('success', 'Signature uploaded and contract activated.');
    }

    public function updateStatus(Request $request, Contract $contract)
    {
        $request->validate([
            'status' => 'required|in:draft,pending_signature,active,expired,terminated,completed',
        ]);

        $contract->update(['status' => $request->status]);

        return back()->with('success', 'Contract status updated.');
    }

    public function loadTemplate(Request $request)
    {
        $template = ContractTemplate::findOrFail($request->template_id);
        
        return response()->json([
            'content' => $template->content,
            'terms' => $template->default_terms,
        ]);
    }

    public function expiringSoon()
    {
        $contracts = Contract::expiringSoon()
            ->with(['creator', 'currency'])
            ->latest('end_date')
            ->paginate(20);

        return view('admin::contracts.expiring', compact('contracts'));
    }
}
