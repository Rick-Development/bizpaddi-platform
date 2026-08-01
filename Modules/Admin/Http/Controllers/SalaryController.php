<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\SalaryDisbursement;
use Modules\Accounting\Services\SafeHavenService;
use App\Models\User;
use Illuminate\Support\Str;

class SalaryController extends Controller
{
    protected $safeHavenService;

    public function __construct(SafeHavenService $safeHavenService)
    {
        $this->safeHavenService = $safeHavenService;
    }

    public function index(Request $request)
    {
        $query = SalaryDisbursement::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('payment_date', $request->date);
        }

        $payments = $query->paginate(15);

        return view('admin::salaries.index', compact('payments'));
    }

    public function create()
    {
        $users = User::with('bankAccounts')
                     ->whereHas('bankAccounts') // Only show users with bank accounts
                     ->get();

        return view('admin::salaries.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
            'payment_date' => 'required|date',
        ]);

        $user = User::with('bankAccounts')->findOrFail($request->user_id);
        
        // Get primary or first bank account
        $bankAccount = $user->bankAccounts()->where('is_primary', true)->first() 
                       ?? $user->bankAccounts()->first();

        if (!$bankAccount) {
            return back()->with('error', 'Selected user has no linked bank account.');
        }

        // 1. Verify Account (Name Enquiry)
        $verification = $this->safeHavenService->verifyAccount($bankAccount->bank_code, $bankAccount->account_number);

        if (!($verification['success'] ?? false)) {
             return back()->with('error', 'Bank account verification failed: ' . ($verification['message'] ?? 'Unknown error'));
        }
        
        $verifiedName = $verification['data']['accountName'] ?? $verification['data']['account_name'] ?? 'Unknown User';

        // 2. Process Disbursement
        $reference = 'SAL_' . Str::random(12);
        
        // Use the sessionId from the successful Name Enquiry
        $nameEnquiryRef = $verification['data']['sessionId'] ?? null;

        if (!$nameEnquiryRef) {
             return back()->with('error', 'Bank account verification failed: No session ID returned from Name Enquiry.');
        }

        // SafeHaven Debit Account (Admin's account provided in config or logic). 
        // Assuming SafeHavenService expects 'debit_account' in payload specifically for transfers if needed, 
        // or uses a default internal one. Checking `processDisbursement` signature...
        // It requires 'debitAccountNumber'. Taking it from config or env would be best, 
        // but for now I'll check if I need to hardcode a placeholder or if config has it.
        // I will assume config('services.safehaven.debit_account') exists or use a placeholder to be updated.
        $debitAccount = config('services.safehaven.debit_account_number'); 

        if (!$debitAccount) {
             return back()->with('error', 'System Error: Debit Account not configured.');
        }

        $payload = [
            'amount' => $request->amount,
            'bank_code' => $bankAccount->bank_code,
            'account_number' => $bankAccount->account_number,
            'account_name' => $verifiedName,
            'narration' => $request->description ?? 'Salary Payment',
            'name_enquiry_reference' => $nameEnquiryRef,
            'debit_account' => $debitAccount,
        ];

        $transfer = $this->safeHavenService->processDisbursement($payload);

        if (!($transfer['success'] ?? false)) {
             return back()->with('error', 'Transfer failed: ' . ($transfer['message'] ?? 'Unknown error'));
        }

        // Map SafeHaven status to our internal statuses if needed
        $apiStatus = $transfer['data']['status'] ?? 'processing';
        $internalStatus = strtolower($apiStatus);
        
        // Handle variations (Successful vs completed)
        if ($internalStatus === 'payout-successful' || $internalStatus === 'successful' || $internalStatus === 'success') {
            $internalStatus = 'successful';
        }

        // 3. Store Record
        $disbursement = SalaryDisbursement::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'currency' => 'NGN',
            'bank_code' => $bankAccount->bank_code,
            'bank_name' => $bankAccount->bank_name,
            'account_number' => $bankAccount->account_number,
            'account_name' => $verifiedName,
            'session_id' => $transfer['data']['session_id'] ?? null,
            'reference' => $transfer['data']['reference'] ?? $reference,
            'status' => $internalStatus, 
            'description' => $request->description,
            'payment_date' => $request->payment_date,
        ]);

        // 4. Create a unified Transaction record for accounting/reporting
        \Modules\Accounting\Models\Transaction::create([
            'transaction_number' => 'TRX-' . strtoupper(Str::random(8)),
            'transaction_date' => now(),
            'payable_type' => SalaryDisbursement::class,
            'payable_id' => $disbursement->id,
            'reference' => $disbursement->reference,
            'amount' => $disbursement->amount,
            'currency_code' => 'NGN',
            'status' => 'completed', 
            'type' => 'debit',
            'source' => 'salary',
            'provider' => 'safehaven',
            'provider_reference' => $disbursement->session_id,
            'description' => 'Salary Disbursement to ' . $user->name . ': ' . $request->description,
            'paid_at' => now(),
        ]);

        return redirect()->route('admin.accounting.salaries.index')->with('success', 'Salary disbursement initiated successfully.');
    }

    public function show($id)
    {
        $payment = SalaryDisbursement::with('user')->findOrFail($id);
        return view('admin::salaries.show', compact('payment'));
    }
}
