<?php

namespace Modules\Admin\Http\Controllers\Api\Accounting;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Api\ApiController;
use Modules\Accounting\Models\Invoice;

class InvoiceController extends ApiController
{
    /**
     * GET /api/v1/admin/accounting/invoices
     */
    public function index()
    {
        $invoices = Invoice::with('client')->latest()->paginate(20);
        return $this->sendResponse($invoices, 'Invoices retrieved successfully.');
    }

    /**
     * POST /api/v1/admin/accounting/invoices
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'due_date' => 'required|date',
            // ... other validations
        ]);

        $data = $request->all();
        $data['created_by'] = $request->user()->id;

        $invoice = Invoice::create($data);

        return $this->sendResponse($invoice, 'Invoice created successfully.', 201);
    }

    /**
     * GET /api/v1/admin/accounting/invoices/{id}
     */
    public function show($id)
    {
        $invoice = Invoice::with(['items', 'client'])->find($id);
        if (!$invoice) return $this->sendError('Invoice not found.', 404);
        return $this->sendResponse($invoice, 'Invoice details retrieved successfully.');
    }

    /**
     * PUT /api/v1/admin/accounting/invoices/{id}
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        if (!$invoice) return $this->sendError('Invoice not found.', 404);

        $invoice->update($request->all());
        return $this->sendResponse($invoice, 'Invoice updated successfully.');
    }

    /**
     * DELETE /api/v1/admin/accounting/invoices/{id}
     */
    public function destroy($id)
    {
        $invoice = Invoice::find($id);
        if (!$invoice) return $this->sendError('Invoice not found.', 404);
        
        $invoice->delete();
        return $this->sendResponse([], 'Invoice deleted successfully.');
    }
}
