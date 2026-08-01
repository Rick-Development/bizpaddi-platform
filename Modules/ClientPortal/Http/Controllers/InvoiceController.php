<?php

namespace Modules\ClientPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Accounting\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Accounting\Services\SafeHavenService;
use Modules\Accounting\Models\VirtualAccount;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    protected $safeHavenService;

    public function __construct(SafeHavenService $safeHavenService)
    {
        $this->safeHavenService = $safeHavenService;
    }

    protected function getInvoice($id)
    {
        $client = auth()->guard('client')->user();
        return $client->invoices()->with(['items', 'currency'])->findOrFail($id);
    }

    public function index()
    {
        $client = auth()->guard('client')->user();
        $invoices = $client->invoices()->latest()->paginate(10);

        return view('clientportal::invoices.index', compact('invoices'));
    }

    public function show($id)
    {
        $client = auth()->guard('client')->user();
        $invoice = $client->invoices()->with(['items', 'currency'])->findOrFail($id);

        return view('clientportal::invoices.show', compact('invoice'));
    }

    public function downloadPDF($id)
    {
        $client = auth()->guard('client')->user();
        $invoice = $client->invoices()->with(['items', 'currency'])->findOrFail($id);
        
        // Reuse admin PDF view for consistency
        $pdf = Pdf::loadView('admin::accounting.invoices.pdf', compact('invoice'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function pay($id)
    {
        $client = auth()->guard('client')->user();
        $invoice = $client->invoices()->with(['currency'])->findOrFail($id);

        if ($invoice->status === 'paid') {
            return redirect()->route('client.invoices.show', $invoice->id)->with('info', 'This invoice is already paid.');
        }

        // Check if virtual account exists
        $virtualAccount = VirtualAccount::where('payable_type', Invoice::class)
            ->where('payable_id', $invoice->id)
            ->where('status', 'active')
            ->first();

        // If virtual account doesn't exist OR has expired, create a new one
        if (!$virtualAccount || ($virtualAccount->expires_at && $virtualAccount->expires_at->isPast())) {
            // Mark old account as expired if it exists
            if ($virtualAccount && $virtualAccount->expires_at && $virtualAccount->expires_at->isPast()) {
                $virtualAccount->update(['status' => 'expired']);
            }

            // Generate Virtual Account
            $reference = 'inv_' . Str::random(10) . '_' . $invoice->id; // Unique ref
            
            // Get Settlement Settings
            $bankCode = \Modules\Settings\Models\Setting::get('safehaven_settlement_bank_code');
            $accountNumber = \Modules\Settings\Models\Setting::get('safehaven_settlement_account_number');

            if (!$bankCode || !$accountNumber) {
                return back()->with('error', 'Payment configuration missing: Settlement details not set in admin settings.');
            }

            // Prepare payload
            $payload = [
                'amount' => $invoice->total_amount,
                'reference' => $reference,
                'settlement_bank_code' => $bankCode,
                'settlement_account_number' => $accountNumber,
                'valid_for' => 900, // 15 minutes as per user request
                'callback_url' => route('webhooks.safehaven'), 
            ];

            try {
                $response = $this->safeHavenService->initiateCollection($payload);

                if ($response['success']) {
                    $virtualAccount = VirtualAccount::create([
                        'payable_type' => Invoice::class,
                        'payable_id' => $invoice->id,
                        'bank_name' => $response['data']['bank_name'],
                        'account_name' => $response['data']['account_name'],
                        'account_number' => $response['data']['account_number'],
                        'reference' => $response['data']['reference'],
                        'session_id' => $response['data']['session_id'],
                        'currency_code' => $invoice->currency->code ?? 'NGN',
                        'expires_at' => now()->addSeconds(900), // 15 minutes
                    ]);
                } else {
                    return back()->with('error', 'Unable to initiate payment: ' . $response['message']);
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Payment service unavailable: ' . $e->getMessage());
            }
        }

        // Ensure expires_at is set (for old virtual accounts that don't have it)
        if (!$virtualAccount->expires_at) {
            $virtualAccount->update([
                'expires_at' => now()->addSeconds(900)
            ]);
            $virtualAccount->refresh();
        }

        // Calculate remaining seconds for countdown
        $remainingSeconds = (int) max(0, $virtualAccount->expires_at->diffInSeconds(now(), false) * -1);

        return view('clientportal::invoices.pay', compact('invoice', 'virtualAccount', 'remainingSeconds'));
    }

    public function confirmPayment($id)
    {
        $invoice = $this->getInvoice($id);

        if ($invoice->status === 'paid') {
            return redirect()->route('client.invoices.show', $invoice->id)->with('success', 'This invoice has already been paid.');
        }

        // Get the virtual account for this invoice
        $virtualAccount = VirtualAccount::where('payable_type', Invoice::class)
            ->where('payable_id', $invoice->id)
            ->where('status', 'active')
            ->first();

        if (!$virtualAccount) {
            return redirect()->route('client.invoices.show', $invoice->id)->with('error', 'No payment information found.');
        }

        if (!$virtualAccount->session_id) {
            return redirect()->route('client.invoices.show', $invoice->id)->with('error', 'Invalid payment session. Please try again.');
        }

        // Check payment status using the session_id from SafeHaven
        try {
            $response = $this->safeHavenService->checkVirtualAccountStatus($virtualAccount->session_id);

            if ($response['success'] && isset($response['data']['status'])) {
                $status = strtolower($response['data']['status']);
                $responseCode = $response['data']['responseCode'] ?? null;

                if (in_array($status, ['successful', 'completed', 'paid']) || $responseCode === '00') {
                    // Mark invoice as paid
                    $invoice->markAsPaid();
                    $virtualAccount->update(['status' => 'completed']);
                    
                    return redirect()->route('client.invoices.show', $invoice->id)
                        ->with('success', 'Payment confirmed! Your invoice has been marked as paid.');
                }
            }

            return redirect()->route('client.invoices.pay', $invoice->id)
                ->with('info', 'Payment not yet confirmed. Please wait a few moments and try again.');

        } catch (\Exception $e) {
            return redirect()->route('client.invoices.pay', $invoice->id)
                ->with('error', 'Unable to verify payment status. Please try again later.');
        }
    }

    public function checkPaymentStatus($id)
    {
        $invoice = $this->getInvoice($id);
        
        if ($invoice->status === 'paid') {
            return response()->json([
                'status' => 'completed',
                'message' => 'Payment successful',
                'redirect_url' => route('client.invoices.show', $invoice->id)
            ]);
        }

        $virtualAccount = VirtualAccount::where('payable_type', Invoice::class)
            ->where('payable_id', $invoice->id)
            ->where('status', 'active')
            ->first();

        if (!$virtualAccount || !$virtualAccount->session_id) {
             return response()->json(['status' => 'pending']);
        }

        try {
            $response = $this->safeHavenService->checkVirtualAccountStatus($virtualAccount->session_id);

            if ($response['success'] && isset($response['data']['status'])) {
                $status = strtolower($response['data']['status']);
                $responseCode = $response['data']['transactions']['responseCode'] ?? $response['data']['responseCode'] ?? null;
                
                // Allow 'pending' ONLY if responseCode is '00'. Strict check for others.
                if (in_array($status, ['successful', 'completed', 'paid']) || $responseCode === '00') {
                    // Record Transaction
                    if (!\Modules\Accounting\Models\Transaction::where('reference', $virtualAccount->reference)->exists()) {
                         \Modules\Accounting\Models\Transaction::create([
                            'payable_type' => Invoice::class,
                            'payable_id' => $invoice->id,
                            'reference' => $virtualAccount->reference,
                            'amount' => $response['data']['amount'] ?? $invoice->total_amount,
                            'currency_code' => $invoice->currency->code ?? 'NGN',
                            'status' => 'successful',
                            'type' => 'credit',
                            'provider' => 'safehaven',
                            'provider_reference' => $virtualAccount->session_id,
                            'description' => 'Invoice Payment via SafeHaven Virtual Account',
                            'metadata' => $response['data'],
                            'paid_at' => now(),
                        ]);
                    }

                    // Mark invoice as paid
                    $invoice->markAsPaid();
                    $virtualAccount->update(['status' => 'completed']);
                    
                    return response()->json([
                        'status' => 'completed',
                        'message' => 'Payment confirmed!',
                        'redirect_url' => route('client.invoices.show', $invoice->id)
                    ]);
                }
            }
        } catch (\Exception $e) {
            // log error
        }

        return response()->json(['status' => 'pending']);
    }
}
