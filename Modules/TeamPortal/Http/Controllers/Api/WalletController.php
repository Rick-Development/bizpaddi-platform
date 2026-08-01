<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Accounting\Models\VirtualAccount;
use Modules\Accounting\Models\Transaction;

class WalletController extends ApiController
{
    /**
     * GET /api/v1/team/wallet
     * Get wallet balance and virtual account details for the team member.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Polymorphic relationship: user -> virtual account
        $virtualAccount = VirtualAccount::where('payable_type', $user->getMorphClass())
            ->where('payable_id', $user->id)
            ->first();

        // Calculate balance from transactions
        // Note: this assumes a basic transaction sum. Adjust according to actual business logic.
        $balance = Transaction::where('payable_type', $user->getMorphClass())
            ->where('payable_id', $user->id)
            ->sum('amount'); // This is a placeholder logic

        return $this->sendResponse([
            'balance' => $balance,
            'virtual_account' => $virtualAccount,
        ], 'Wallet details retrieved successfully.');
    }

    /**
     * GET /api/v1/team/wallet/transactions
     * List transaction history.
     */
    public function transactions(Request $request)
    {
        $user = $request->user();

        $transactions = Transaction::where('payable_type', $user->getMorphClass())
            ->where('payable_id', $user->id)
            ->latest()
            ->paginate(15);

        return $this->sendResponse($transactions, 'Transaction history retrieved successfully.');
    }
}
