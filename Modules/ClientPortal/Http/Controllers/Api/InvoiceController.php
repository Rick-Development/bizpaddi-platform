<?php

namespace Modules\ClientPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Accounting\Models\Invoice;

class InvoiceController extends ApiController
{
    public function index(Request $request)
    {
        $clientId = $request->user()->id;
        
        $invoices = Invoice::where('client_id', $clientId)
            ->with(['currency', 'items'])
            ->latest()
            ->paginate(10);

        return $this->sendResponse($invoices, 'Invoices retrieved successfully.');
    }

    public function show($id)
    {
        $clientId = auth()->id();
        
        $invoice = Invoice::where('client_id', $clientId)
            ->with(['currency', 'items'])
            ->find($id);

        if (!$invoice) {
            return $this->sendError('Invoice not found.');
        }

        return $this->sendResponse($invoice, 'Invoice details retrieved successfully.');
    }
}
