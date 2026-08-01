<?php

namespace Modules\ClientPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Transaction;
use Modules\Accounting\Models\Invoice;

class TransactionController extends Controller
{
    public function index()
    {
        $client = auth()->guard('client')->user();
        
        // Get transactions for all client invoices
        $transactions = Transaction::whereHasMorph('payable', [Invoice::class], function ($query) use ($client) {
            $query->where('client_id', $client->id);
        })->latest()->paginate(15);

        return view('clientportal::transactions.index', compact('transactions'));
    }

    public function show($id)
    {
        $client = auth()->guard('client')->user();
        
        $transaction = Transaction::whereHasMorph('payable', [Invoice::class], function ($query) use ($client) {
            $query->where('client_id', $client->id);
        })->findOrFail($id);

        return view('clientportal::transactions.show', compact('transaction'));
    }
}
