<?php

namespace Modules\Admin\Http\Controllers\Api\Accounting;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Api\ApiController;
use Modules\Accounting\Models\Account;

class BalanceSheetController extends ApiController
{
    /**
     * GET /api/v1/admin/accounting/balance-sheet
     * Generate balance sheet data.
     */
    public function index(Request $request)
    {
        // Simple mock of balance sheet aggregation
        $assets = Account::whereHas('category', function($q) { $q->where('type', 'asset'); })->get();
        $liabilities = Account::whereHas('category', function($q) { $q->where('type', 'liability'); })->get();
        $equity = Account::whereHas('category', function($q) { $q->where('type', 'equity'); })->get();

        $data = [
            'assets' => [
                'accounts' => $assets,
                'total' => $assets->sum('current_balance')
            ],
            'liabilities' => [
                'accounts' => $liabilities,
                'total' => $liabilities->sum('current_balance')
            ],
            'equity' => [
                'accounts' => $equity,
                'total' => $equity->sum('current_balance')
            ]
        ];

        return $this->sendResponse($data, 'Balance sheet generated successfully.');
    }
}
