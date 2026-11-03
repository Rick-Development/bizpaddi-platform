<?php

namespace Modules\SuperAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TenantPayment;
use App\Services\Billing\BillwayGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = TenantPayment::with(['tenant', 'subscription.plan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('gateway_reference', 'like', "%{$search}%")
                  ->orWhereHas('tenant', function ($t) use ($search) {
                      $t->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->latest()->paginate(20)->withQueryString();

        return view('superadmin::transactions.index', compact('transactions'));
    }

    public function show(TenantPayment $payment)
    {
        $payment->load(['tenant', 'subscription.plan']);
        return view('superadmin::transactions.show', compact('payment'));
    }

    /**
     * Billway incoming webhook receiver.
     */
    public function handleWebhook(Request $request, BillwayGateway $gateway)
    {
        Log::info('Billway Webhook incoming', ['payload' => $request->all()]);

        $webhookSecret = \Modules\Settings\Models\Setting::get('billway_webhook_secret', config('services.billway.webhook_secret'));
        if (!empty($webhookSecret)) {
            $signature = $request->header('X-Billway-Signature') ?? $request->header('X-Webhook-Signature');
            $computed = hash_hmac('sha256', $request->getContent(), $webhookSecret);
            if (!$signature || !hash_equals($computed, $signature)) {
                Log::warning('Billway Webhook rejected due to invalid signature');
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 401);
            }
        }

        $status = strtolower((string) ($request->input('data.status') ?? $request->input('status') ?? 'successful'));
        if (!in_array($status, ['success', 'successful', 'completed', 'paid'], true)) {
            return response()->json(['status' => 'ignored', 'message' => 'Non-success event status'], 200);
        }

        $reference = $request->input('data.reference') 
            ?? $request->input('reference') 
            ?? $request->input('data.transaction_reference');

        if (!$reference) {
            return response()->json(['status' => 'ignored', 'message' => 'No reference found in payload'], 200);
        }

        $result = $gateway->verifyAndActivate($reference, true);

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}
