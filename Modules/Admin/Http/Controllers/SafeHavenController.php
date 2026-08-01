<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Services\SafeHavenService;

class SafeHavenController extends Controller
{
    protected SafeHavenService $safeHavenService;

    public function __construct(SafeHavenService $safeHavenService)
    {
        $this->safeHavenService = $safeHavenService;
    }

    public function index(Request $request)
    {
        $page = $request->input('page', 0); // SafeHaven uses 0-based pagination
        $limit = 25;

        // Date filters - default to last 30 days
        $fromDate = $request->input('fromDate', now()->subDays(30)->format('Y-m-d'));
        $toDate = $request->input('toDate', now()->format('Y-m-d'));
        $type = $request->input('type'); // 'Credit' or 'Debit'

        // Fetch transactions from SafeHaven API
        $response = $this->safeHavenService->getTransactions([
            'limit' => $limit,
            'page' => $page,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'type' => $type
        ]);

        $transactions = [];
        $meta = [];

        if ($response['success']) {
            $transactions = $response['data'];
            $meta = $response['meta'] ?? [];
        }

        return view('admin::bank.index', compact('transactions', 'meta', 'fromDate', 'toDate', 'type'));
    }
}
