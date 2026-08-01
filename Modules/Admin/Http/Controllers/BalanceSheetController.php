<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Accounting\Models\Account;
use Modules\Accounting\Models\AccountCategory;

class BalanceSheetController extends Controller
{
    public function index()
    {
        $categories = AccountCategory::with(['accounts' => function($query) {
            $query->active()->orderBy('code');
        }])->orderBy('name')->get();

        $totalAssets = 0;
        $totalLiabilities = 0;
        $totalEquity = 0;

        foreach ($categories as $category) {
            foreach ($category->accounts as $account) {
                $balance = $account->calculateBalance();
                
                // Simple categorization - you can enhance this
                if (str_contains(strtolower($category->name), 'asset')) {
                    $totalAssets += $balance;
                } elseif (str_contains(strtolower($category->name), 'liability')) {
                    $totalLiabilities += $balance;
                } elseif (str_contains(strtolower($category->name), 'equity')) {
                    $totalEquity += $balance;
                }
            }
        }

        // Add accounts receivable (unpaid invoices)
        $accountsReceivable = \Modules\Accounting\Models\Invoice::whereIn('status', ['sent', 'overdue'])
            ->sum('total_amount');
        $totalAssets += $accountsReceivable;

        // Get invoice and quote summaries
        $paidInvoicesTotal = \Modules\Accounting\Models\Invoice::where('status', 'paid')->sum('total_amount');
        $unpaidInvoicesTotal = \Modules\Accounting\Models\Invoice::whereIn('status', ['draft', 'sent', 'overdue'])->sum('total_amount');
        $pendingQuotesTotal = \Modules\Accounting\Models\Quote::whereIn('status', ['draft', 'sent'])->sum('total_amount');

        return view('admin::accounting.balance-sheet', compact(
            'categories',
            'totalAssets',
            'totalLiabilities',
            'totalEquity',
            'accountsReceivable',
            'paidInvoicesTotal',
            'unpaidInvoicesTotal',
            'pendingQuotesTotal'
        ));
    }
}
