<?php

namespace Modules\TeamPortal\Http\Controllers\BDM\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ClientPortal\Models\ServiceOrder;
use Modules\Accounting\Models\Quote;
use Modules\Accounting\Models\Invoice;

class ServiceOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceOrder::with(['client', 'service']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);

        return view('teamportal::bdm.accounting.service-orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = ServiceOrder::with(['client', 'service', 'quote', 'invoice'])->findOrFail($id);

        return view('teamportal::bdm.accounting.service-orders.show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = ServiceOrder::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,reviewed,quoted,invoiced,contracted,completed,cancelled',
            'admin_notes' => 'nullable|string',
            'admin_feedback' => 'nullable|string',
        ]);

        $order->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'admin_feedback' => $request->admin_feedback,
        ]);

        return redirect()->back()->with('success', 'Order updated successfully');
    }

    public function convertToQuote($id)
    {
        $order = ServiceOrder::findOrFail($id);

        // Here we would typically redirect to the Quote Create page 
        // passing order details to pre-fill the form.
        // For now, we'll just redirect to the quotes create route with a query param
        
        return redirect()->route('team.bdm.accounting.quotes.create', ['order_id' => $order->id]);
    }
    
    public function convertToInvoice($id)
    {
        $order = ServiceOrder::with(['client', 'service'])->findOrFail($id);
        
        // Check if already converted
        if ($order->invoice_id) {
            return redirect()->route('team.bdm.accounting.invoices.show', $order->invoice_id)
                ->with('info', 'This order has already been converted to an invoice.');
        }
        
        // Create invoice
        $invoice = Invoice::create([
            'client_id' => $order->client_id,
            'client_name' => $order->client->name,
            'client_email' => $order->client->email,
            'client_address' => $order->client->address ?? '',
            'client_phone' => $order->client->phone ?? '',
            'currency_id' => $order->service->currency_id ?? 1,
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'status' => 'draft',
            'tax_rate' => 0,
            'discount_amount' => 0,
            'created_by' => auth()->id(),
            'notes' => "Generated from Service Order #{$order->id}",
        ]);
        
        // Create invoice item from service
        $servicePrice = $order->service->price ?? 0;
        $invoice->items()->create([
            'description' => $order->service->name,
            'quantity' => 1,
            'unit_price' => $servicePrice,
            'total' => $servicePrice,
        ]);
        
        // Add feature selections as additional items if they exist
        if ($order->feature_selections && is_array($order->feature_selections)) {
            foreach ($order->feature_selections as $featureId => $selected) {
                if ($selected) {
                    $feature = \Modules\Accounting\Models\FeaturePrice::find($featureId);
                    if ($feature) {
                        $invoice->items()->create([
                            'description' => $feature->feature_name ?? 'Feature',
                            'quantity' => 1,
                            'unit_price' => $feature->price ?? 0,
                            'total' => $feature->price ?? 0,
                        ]);
                    }
                }
            }
        }
        
        // Calculate totals
        $invoice->calculateTotals();
        
        // Link invoice to order
        $order->update([
            'invoice_id' => $invoice->id,
            'status' => 'invoiced'
        ]);
        
        return redirect()->route('team.bdm.accounting.invoices.show', $invoice->id)
            ->with('success', 'Invoice created successfully from service order.');
    }

    public function convertToContract($id)
    {
         $order = ServiceOrder::findOrFail($id);
         
         return redirect()->route('team.bdm.contracts.create', ['order_id' => $order->id]);
    }
}
