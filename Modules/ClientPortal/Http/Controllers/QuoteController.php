<?php

namespace Modules\ClientPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Accounting\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Accounting\Services\SafeHavenService;
use Modules\Accounting\Models\VirtualAccount;
use Illuminate\Support\Str;

class QuoteController extends Controller
{
    protected $safeHavenService;

    public function __construct(SafeHavenService $safeHavenService)
    {
        $this->safeHavenService = $safeHavenService;
    }

    public function index()
    {
        $quotes = Quote::whereHas('client', function($query) {
            $query->where('id', auth()->guard('client')->id());
        })
        ->latest()
        ->paginate(15);
        
        return view('clientportal::quotes.index', compact('quotes'));
    }

    public function show($id)
    {
        $quote = $this->getQuote($id);
        return view('clientportal::quotes.show', compact('quote'));
    }

    public function downloadPDF($id)
    {
        $quote = $this->getQuote($id);
        
        // Reuse the admin PDF view for consistency
        $pdf = Pdf::loadView('admin::accounting.quotes.pdf', compact('quote'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('quote-' . $quote->quote_number . '.pdf');
    }

    public function pay($id)
    {
        $quote = $this->getQuote($id);

        if ($quote->status === 'paid') {
            return redirect()->route('client.quotes.show', $quote->id)->with('info', 'This quote is already paid.');
        }

        // Check if virtual account exists
        $virtualAccount = VirtualAccount::where('payable_type', Quote::class)
            ->where('payable_id', $quote->id)
            ->where('status', 'active')
            ->first();

        if (!$virtualAccount) {
            // Generate Virtual Account
            $reference = 'pt_' . Str::random(10) . '_' . $quote->id; // Unique ref
            
            // Get Settlement Settings
            $bankCode = \Modules\Settings\Models\Setting::get('safehaven_settlement_bank_code');
            $accountNumber = \Modules\Settings\Models\Setting::get('safehaven_settlement_account_number');

            if (!$bankCode || !$accountNumber) {
                return back()->with('error', 'Payment configuration missing: Settlement details not set in admin settings.');
            }

            // Prepare payload
            $payload = [
                'amount' => $quote->total_amount,
                'reference' => $reference,
                'settlement_bank_code' => $bankCode,
                'settlement_account_number' => $accountNumber,
                'valid_for' => 900,
                'callback_url' => route('webhooks.safehaven'),
            ];

            try {
                $response = $this->safeHavenService->initiateCollection($payload);

                if ($response['success']) {
                    $virtualAccount = VirtualAccount::create([
                        'payable_type' => Quote::class,
                        'payable_id' => $quote->id,
                        'bank_name' => $response['data']['bank_name'],
                        'account_name' => $response['data']['account_name'],
                        'account_number' => $response['data']['account_number'],
                        'reference' => $response['data']['reference'],
                        'currency_code' => $quote->currency->code ?? 'NGN',
                    ]);
                } else {
                    return back()->with('error', 'Unable to initiate payment: ' . $response['message']);
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Payment service unavailable: ' . $e->getMessage());
            }
        }

        return view('clientportal::quotes.pay', compact('quote', 'virtualAccount'));
    }

    private function getQuote($id)
    {
        return Quote::whereHas('client', function($query) {
            $query->where('id', auth()->guard('client')->id());
        })->with('items', 'currency')->findOrFail($id);
    }
}
