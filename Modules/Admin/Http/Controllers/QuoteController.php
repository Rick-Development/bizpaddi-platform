<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Accounting\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::with('creator')->latest()->paginate(20);
        return view('admin::accounting.quotes.index', compact('quotes'));
    }

    public function create(Request $request)
    {
        $serviceCategories = \Modules\Accounting\Models\ServiceCategory::orderBy('name')->get();
        $services = \Modules\Accounting\Models\Service::active()->get();
        $featurePrices = \Modules\Accounting\Models\FeaturePrice::active()->get();
        $clients = \Modules\Clients\Models\Client::where('is_active', true)->get();
        $currencies = \Modules\Accounting\Models\Currency::active()->get();
        
        $order = null;
        if ($request->has('order_id')) {
            $order = \Modules\ClientPortal\Models\ServiceOrder::with('client', 'service')->find($request->order_id);
        }

        $defaultNotes = \Modules\Settings\Models\Setting::get('default_quote_notes', '');
        $defaultTerms = \Modules\Settings\Models\Setting::get('default_quote_terms', '');

        return view('admin::accounting.quotes.create', compact('serviceCategories', 'services', 'featurePrices', 'clients', 'currencies', 'order', 'defaultNotes', 'defaultTerms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'service_category_id' => 'nullable|exists:service_categories,id',
            'service_id' => 'nullable|exists:services,id',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'client_address' => 'nullable|string',
            'client_phone' => 'nullable|string',
            'issue_date' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:issue_date',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.feature_price_id' => 'nullable|exists:feature_prices,id',
            'items.*.title' => 'required|string',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'order_id' => 'nullable|exists:service_orders,id',
        ]);

        // Default currency if not provided
        if (empty($validated['currency_id'])) {
            $validated['currency_id'] = \Modules\Accounting\Models\Currency::where('is_default', true)->value('id');
        }

        $quote = Quote::create([
            'client_id' => $request->client_id,
            'currency_id' => $validated['currency_id'],
            'service_category_id' => $validated['service_category_id'] ?? null,
            'service_id' => $validated['service_id'] ?? null,
            'client_name' => $request->client_name,
            'client_email' => $request->client_email,
            'client_address' => $request->client_address,
            'client_phone' => $request->client_phone,
            'issue_date' => $request->issue_date,
            'valid_until' => $request->valid_until,
            'tax_rate' => $request->tax_rate ?? 0,
            'discount_amount' => $request->discount_amount ?? 0,
            'notes' => $request->notes,
            'terms_conditions' => $request->terms_conditions,
            'created_by' => auth('admin')->id(),
            'status' => 'draft',
        ]);

        foreach ($request->items as $item) {
            $quote->items()->create([
                'service_id' => $item['service_id'] ?? null,
                'feature_price_id' => $item['feature_price_id'] ?? null,
                'title' => $item['title'],
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        $quote->calculateTotals();

        // Link ServiceOrder if present
        if ($request->filled('order_id')) {
            $order = \Modules\ClientPortal\Models\ServiceOrder::find($request->order_id);
            if ($order) {
                $order->update([
                    'quote_id' => $quote->id,
                    'status' => 'quoted',
                ]);
            }
        }

        return redirect()->route('admin.accounting.quotes.show', $quote)
            ->with('success', 'Quote created successfully.');
    }

    public function show(Quote $quote)
    {
        $quote->load('items.service', 'creator', 'currency', 'serviceCategory', 'service');
        return view('admin::accounting.quotes.show', compact('quote'));
    }

    public function edit(Quote $quote)
    {
        $quote->load('items');
        $serviceCategories = \Modules\Accounting\Models\ServiceCategory::orderBy('name')->get();
        $services = \Modules\Accounting\Models\Service::active()->get();
        $featurePrices = \Modules\Accounting\Models\FeaturePrice::active()->get();
        $clients = \Modules\Clients\Models\Client::where('is_active', true)->get();
        $currencies = \Modules\Accounting\Models\Currency::active()->get();
        return view('admin::accounting.quotes.edit', compact('quote', 'serviceCategories', 'services', 'featurePrices', 'clients', 'currencies'));
    }

    public function update(Request $request, Quote $quote)
    {
        $validated = $request->validate([
            'currency_id' => 'nullable|exists:currencies,id',
            'client_id' => 'nullable|exists:clients,id',
            'service_category_id' => 'nullable|exists:service_categories,id',
            'service_id' => 'nullable|exists:services,id',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'client_address' => 'nullable|string',
            'client_phone' => 'nullable|string',
            'issue_date' => 'required|date',
            'valid_until' => 'required|date',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.feature_price_id' => 'nullable|exists:feature_prices,id',
            'items.*.title' => 'required|string',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Default currency if not provided
        if (empty($validated['currency_id'])) {
            $validated['currency_id'] = \Modules\Accounting\Models\Currency::where('is_default', true)->value('id');
        }

        $quote->update(collect($validated)->except('items')->toArray());

        // Sync Items: Delete existing and recreate
        $quote->items()->delete();

        foreach ($request->items as $item) {
            $quote->items()->create([
                'service_id' => $item['service_id'] ?? null,
                'feature_price_id' => $item['feature_price_id'] ?? null,
                'title' => $item['title'],
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        $quote->calculateTotals();

        return redirect()->route('admin.accounting.quotes.show', $quote)
            ->with('success', 'Quote updated successfully.');
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();
        return redirect()->route('admin.accounting.quotes.index')
            ->with('success', 'Quote deleted successfully.');
    }

    public function send(Quote $quote)
    {
        // TODO: Implement email sending
        $quote->update(['status' => 'sent']);
        return back()->with('success', 'Quote sent successfully.');
    }

    public function approve(Quote $quote)
    {
        $quote->markAsApproved();
        return back()->with('success', 'Quote approved successfully.');
    }

    public function convertToInvoice(Quote $quote)
    {
        $invoice = $quote->convertToInvoice();
        return redirect()->route('admin.accounting.invoices.show', $invoice)
            ->with('success', 'Quote converted to invoice successfully.');
    }

    public function downloadPDF(Quote $quote)
    {
        $quote->load('items.service', 'creator', 'currency', 'serviceCategory', 'service');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin::accounting.quotes.pdf', compact('quote'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('quote-' . $quote->quote_number . '.pdf');
    }
}
