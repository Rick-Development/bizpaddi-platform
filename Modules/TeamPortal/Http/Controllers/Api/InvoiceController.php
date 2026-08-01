<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Accounting\Models\Invoice;

class InvoiceController extends ApiController
{
    /**
     * GET /api/v1/team/invoices
     * List invoices related to the user's projects/clients.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('You do not have permission to view invoices.', 403);
        }

        $invoices = Invoice::where('created_by', $user->id)
            ->latest()
            ->paginate(15);

        return $this->sendResponse($invoices, 'Invoices retrieved successfully.');
    }

    /**
     * GET /api/v1/team/invoices/{id}
     * Get details of a specific invoice.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('You do not have permission to view invoices.', 403);
        }

        $invoice = Invoice::with(['items', 'client'])->where('created_by', $user->id)->find($id);

        if (!$invoice) {
            return $this->sendError('Invoice not found.', 404);
        }

        return $this->sendResponse($invoice, 'Invoice details retrieved successfully.');
    }
}
