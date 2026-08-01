<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Accounting\Models\Transaction;
use Modules\Accounting\Models\Account;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['account', 'creator'])->latest()->paginate(20);
        
        // Get paid invoices
        $paidInvoices = \Modules\Accounting\Models\Invoice::where('status', 'paid')
            ->with('currency')
            ->latest('paid_at')
            ->limit(10)
            ->get();
        
        // Get unpaid invoices
        $unpaidInvoices = \Modules\Accounting\Models\Invoice::whereIn('status', ['draft', 'sent', 'overdue'])
            ->with('currency')
            ->latest()
            ->limit(10)
            ->get();
        
        // Get approved quotes
        $approvedQuotes = \Modules\Accounting\Models\Quote::where('status', 'approved')
            ->with('currency')
            ->latest('approved_at')
            ->limit(10)
            ->get();
        
        // Get pending quotes
        $pendingQuotes = \Modules\Accounting\Models\Quote::whereIn('status', ['draft', 'sent'])
            ->with('currency')
            ->latest()
            ->limit(10)
            ->get();
        
        return view('admin::accounting.transactions.index', compact(
            'transactions',
            'paidInvoices',
            'unpaidInvoices',
            'approvedQuotes',
            'pendingQuotes'
        ));
    }

    public function create()
    {
        $accounts = Account::active()->orderBy('name')->get();
        return view('admin::accounting.transactions.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'reference' => 'nullable|string',
            'transaction_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $validated['created_by'] = auth('admin')->id();
        $transaction = Transaction::create($validated);

        // Update account balance if completed
        if ($transaction->status === 'completed') {
            $transaction->account->updateBalance();
        }

        return redirect()->route('admin.accounting.transactions.index')
            ->with('success', 'Transaction created successfully.');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['account', 'creator']);
        return view('admin::accounting.transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $accounts = Account::active()->orderBy('name')->get();
        return view('admin::accounting.transactions.edit', compact('transaction', 'accounts'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'reference' => 'nullable|string',
            'transaction_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $oldStatus = $transaction->status;
        $transaction->update($validated);

        // Update account balance if status changed
        if ($oldStatus !== $transaction->status) {
            $transaction->account->updateBalance();
        }

        return redirect()->route('admin.accounting.transactions.index')
            ->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        $account = $transaction->account;
        $transaction->delete();
        $account->updateBalance();

        return redirect()->route('admin.accounting.transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }

    public function markComplete(Transaction $transaction)
    {
        $transaction->update(['status' => 'completed']);
        $transaction->account->updateBalance();

        return back()->with('success', 'Transaction marked as completed.');
    }
}
