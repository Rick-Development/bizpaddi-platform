<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Accounting\Models\VirtualAccount;
use Modules\Accounting\Models\Invoice;
use Modules\Accounting\Models\Quote;

class WebhookController extends Controller
{
    public function handleSafeHaven(Request $request)
    {
        Log::info('SafeHaven Webhook Received', $request->all());

        $payload = $request->all();
        
        // SafeHaven payload structure can vary, but usually has externalReference or reference
        $reference = $payload['externalReference'] ?? $payload['reference'] ?? null;

        if (!$reference) {
            Log::warning('SafeHaven Webhook: Reference missing in payload');
            return response()->json(['message' => 'Reference not found'], 400);
        }

        $virtualAccount = VirtualAccount::where('reference', $reference)->first();

        if (!$virtualAccount) {
            Log::warning('SafeHaven Webhook: Virtual account not found for reference: ' . $reference);
            return response()->json(['message' => 'Virtual account not found'], 404);
        }

        $payable = $virtualAccount->payable;

        if (!$payable) {
            Log::warning('SafeHaven Webhook: Payable model not found for reference: ' . $reference);
            return response()->json(['message' => 'Payable not found'], 404);
        }

        // Logic based on event type if provided, otherwise assume payment success if reached here
        // SafeHaven events: 'SuccessfulTransfer', etc.
        
        if ($payable instanceof Invoice) {
            if ($payable->status !== 'paid') {
                $payable->markAsPaid();
                Log::info('SafeHaven Webhook: Invoice #' . $payable->invoice_number . ' marked as paid.');
            }
        } elseif ($payable instanceof Quote) {
            if ($payable->status !== 'paid') {
                // Quotes might need markAsPaid logic too
                $payable->update(['status' => 'paid']);
                Log::info('SafeHaven Webhook: Quote #' . $payable->quote_number . ' marked as paid.');
            }
        }

        return response()->json(['message' => 'Webhook processed successfully']);
    }
}
