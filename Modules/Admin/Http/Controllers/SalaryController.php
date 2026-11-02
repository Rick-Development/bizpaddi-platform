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
    protected ?SafeHavenService $safeHavenService;

    public function __construct(?SafeHavenService $safeHavenService = null)
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

        $verifiedName = $bankAccount->account_name ?: $user->name;
        $reference = 'SAL_' . strtoupper(Str::random(10));
        $internalStatus = 'successful';

        // 3. Store Record
        $disbursement = SalaryDisbursement::create([
            'tenant_id' => auth('admin')->user()->tenant_id ?? 1,
            'user_id' => $user->id,
            'amount' => $request->amount,
            'currency' => 'NGN',
            'bank_code' => $bankAccount->bank_code,
            'bank_name' => $bankAccount->bank_name,
            'account_number' => $bankAccount->account_number,
            'account_name' => $verifiedName,
            'session_id' => 'DISB_' . time(),
            'reference' => $reference,
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
