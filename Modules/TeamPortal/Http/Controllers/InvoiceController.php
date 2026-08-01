<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Invoice;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        // RBAC Check
        if (!auth()->user()->can('invoices.view')) {
            abort(403);
        }

        $query = Invoice::query();

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', "%{$request->search}%")
                  ->orWhere('client_name', 'like', "%{$request->search}%");
        }

        $invoices = $query->with('currency')->latest()->paginate(10);

        return view('teamportal::invoices.index', compact('invoices'));
    }

    public function show($id)
    {
        // RBAC Check
        if (!auth()->user()->can('invoices.view')) {
            abort(403);
        }

        $invoice = Invoice::with(['items', 'currency', 'creator'])->findOrFail($id);

        return view('teamportal::invoices.show', compact('invoice'));
    }
}
