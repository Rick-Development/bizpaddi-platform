<?php

namespace Modules\Admin\Http\Controllers\Api\Accounting;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Api\ApiController;
use Modules\Accounting\Models\Transaction;

class TransactionController extends ApiController
{
    public function index()
    {
        $transactions = Transaction::latest()->paginate(50);
        return $this->sendResponse($transactions, 'Transactions retrieved successfully.');
    }

    public function show($id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) return $this->sendError('Transaction not found.', 404);
        return $this->sendResponse($transaction, 'Transaction details retrieved successfully.');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['created_by'] = $request->user()->id;
        $transaction = Transaction::create($data);
        return $this->sendResponse($transaction, 'Transaction created successfully.', 201);
    }

    // Usually transactions shouldn't be updated or deleted, but providing basic skeleton if needed
}
