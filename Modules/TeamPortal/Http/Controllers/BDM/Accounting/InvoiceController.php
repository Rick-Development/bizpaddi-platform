<?php

namespace Modules\TeamPortal\Http\Controllers\BDM\Accounting;

use App\Http\Controllers\Controller;
use Modules\Accounting\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('creator')->latest()->paginate(20);
        return view('teamportal::bdm.accounting.invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        $serviceCategories = \Modules\Accounting\Models\ServiceCategory::orderBy('name')->get();
        $services = \Modules\Accounting\Models\Service::active()->get();
        $featurePrices = \Modules\Accounting\Models\FeaturePrice::active()->get();
        $clients = \Modules\Clients\Models\Client::where('is_active', true)->orderBy('name')->get();
        $currencies = \Modules\Accounting\Models\Currency::active()->get();
        
        $order = null;
        if ($request->has('order_id')) {
            $order = \Modules\ClientPortal\Models\ServiceOrder::with('client', 'service')->find($request->order_id);
        }

        $defaultNotes = \Modules\Settings\Models\Setting::get('default_invoice_notes', '');
        $defaultTerms = \Modules\Settings\Models\Setting::get('default_invoice_terms', '');

        return view('teamportal::bdm.accounting.invoices.create', compact('clients', 'currencies', 'services', 'featurePrices', 'serviceCategories', 'order', 'defaultNotes', 'defaultTerms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'service_category_id' => 'nullable|exists:service_categories,id',
            'service_id' => 'nullable|exists:services,id',
            'client_name' => 'required_without:client_id|string|max:255',
            'client_email' => 'required_without:client_id|email',
            'client_address' => 'nullable|string',
            'client_phone' => 'nullable|string',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'nullable|exists:services,id',
            'items.*.title' => 'required|string',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'order_id' => 'nullable|exists:service_orders,id',
        ]);

        $validated['created_by'] = auth('admin')->id();
        $validated['status'] = 'draft';

        // Default currency if not provided
        if (empty($validated['currency_id'])) {
            $validated['currency_id'] = \Modules\Accounting\Models\Currency::where('is_default', true)->value('id');
        }

        // Auto-fill client details if selected
        if ($request->filled('client_id')) {
            $client = \Modules\Clients\Models\Client::find($request->client_id);
            if ($client) {
                $validated['client_name'] = $client->name;
                $validated['client_email'] = $client->email;
                $validated['client_phone'] = $client->phone;
                $validated['client_address'] = implode("\n", array_filter([$client->address, $client->city, $client->state, $client->zip, $client->country]));
            }
        }

        // Create Invoice
        $invoice = Invoice::create(collect($validated)->except('items')->toArray());

        // Create Items
        foreach ($request->items as $item) {
            $invoice->items()->create([
                'service_id' => $item['service_id'] ?? null,
                'title' => $item['title'],
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                // Total is calculated by model boot event
            ]);
        }
        
        // Calculate totals
        $invoice->calculateTotals();

        // Link ServiceOrder if present
        if ($request->filled('order_id')) {
            $order = \Modules\ClientPortal\Models\ServiceOrder::find($request->order_id);
            if ($order) {
                $order->update([
                    'invoice_id' => $invoice->id,
                    'status' => 'invoiced',
                ]);
            }
        }

        return redirect()->route('team.bdm.accounting.invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('items.service', 'creator', 'currency', 'serviceCategory', 'service');
        return view('teamportal::bdm.accounting.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items');
        $serviceCategories = \Modules\Accounting\Models\ServiceCategory::orderBy('name')->get();
        $services = \Modules\Accounting\Models\Service::active()->get();
        $clients = \Modules\Clients\Models\Client::where('is_active', true)->orderBy('name')->get();
        $currencies = \Modules\Accounting\Models\Currency::active()->get();
        return view('teamportal::bdm.accounting.invoices.edit', compact('invoice', 'clients', 'currencies', 'services', 'serviceCategories'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'currency_id' => 'nullable|exists:currencies,id',
            'service_category_id' => 'nullable|exists:service_categories,id',
            'service_id' => 'nullable|exists:services,id',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'client_address' => 'nullable|string',
            'client_phone' => 'nullable|string',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'nullable|exists:services,id',
            'items.*.title' => 'required|string',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Default currency if not provided
        if (empty($validated['currency_id'])) {
            $validated['currency_id'] = \Modules\Accounting\Models\Currency::where('is_default', true)->value('id');
        }

        // Update Invoice details
        $invoice->update(collect($validated)->except('items')->toArray());

        // Sync Items: Delete existing and recreate
        $invoice->items()->delete();
        
        foreach ($request->items as $item) {
            $invoice->items()->create([
                'service_id' => $item['service_id'] ?? null,
                'title' => $item['title'],
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
            ]);
        }

        // Recalculate totals
        $invoice->calculateTotals();

        return redirect()->route('team.bdm.accounting.invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('team.bdm.accounting.invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    public function send(Invoice $invoice)
    {
        // TODO: Implement email sending
        $invoice->update(['status' => 'sent']);
        return back()->with('success', 'Invoice sent successfully.');
    }

    public function markPaid(Invoice $invoice)
    {
        $invoice->markAsPaid();
        return back()->with('success', 'Invoice marked as paid.');
    }

    public function downloadPDF(Invoice $invoice)
    {
        $invoice->load('items.service', 'creator', 'currency', 'serviceCategory', 'service');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin::accounting.invoices.pdf', compact('invoice'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('Invoice-' . $invoice->invoice_number . '.pdf');
    }
}
