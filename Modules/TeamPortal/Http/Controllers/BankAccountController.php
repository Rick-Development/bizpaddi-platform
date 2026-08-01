<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\TeamPortal\Models\TeamMemberBankAccount;
use Modules\Accounting\Services\SafeHavenService;
use Illuminate\Support\Facades\Log;

class BankAccountController extends Controller
{
    protected $safeHavenService;

    public function __construct(SafeHavenService $safeHavenService)
    {
        $this->safeHavenService = $safeHavenService;
    }

    public function index()
    {
        $account = auth()->user()->bankAccounts()->first();
        return view('teamportal::bank-accounts.index', compact('account'));
    }

    public function create()
    {
        // Check if user already has a bank account
        if (auth()->user()->bankAccounts()->exists()) {
            return redirect()->route('team.bank-accounts.index')
                ->with('error', 'You can only have one bank account. Please delete your existing account to add a new one.');
        }
        
        try {
            $banks = $this->safeHavenService->getBanks();
            
            // Sort banks alphabetically by name
            usort($banks, function($a, $b) {
                $nameA = $a['name'] ?? $a['bankName'] ?? '';
                $nameB = $b['name'] ?? $b['bankName'] ?? '';
                return strcasecmp($nameA, $nameB);
            });
        } catch (\Exception $e) {
            Log::error('Failed to fetch banks: ' . $e->getMessage());
            $banks = [];
        }
        
        return view('teamportal::bank-accounts.create', compact('banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_code' => 'required|string',
            'account_number' => 'required|digits:10',
        ]);

        // Verify account name
        try {
            $verification = $this->safeHavenService->verifyAccount($request->bank_code, $request->account_number);
            
            // Check if verification was successful
            // SafeHaven returns: ['status' => true/false, 'message' => '...', 'data' => [...]]
            if (isset($verification['status']) && $verification['status'] === false) {
                $errorMessage = $verification['message'] ?? 'Account verification failed';
                return back()->withErrors(['account_number' => $errorMessage])->withInput();
            }
            
            // Check if we have the account name in the response
            $accountName = $verification['data']['accountName'] ?? $verification['accountName'] ?? null;
             
             if (!$accountName) {
                 $errorMessage = $verification['message'] ?? $verification['description'] ?? 'Could not verify account name. Please check your account details.';
                 return back()->withErrors(['account_number' => $errorMessage])->withInput();
             }

             // Find bank name from code
             $banks = $this->safeHavenService->getBanks();
             $bankName = 'Unknown Bank';
             foreach($banks as $bank) {
                 $code = $bank['code'] ?? $bank['bankCode'] ?? null;
                 if($code === $request->bank_code) {
                     $bankName = $bank['name'] ?? $bank['bankName'] ?? 'Unknown Bank';
                     break;
                 }
             }

             auth()->user()->bankAccounts()->create([
                 'bank_code' => $request->bank_code,
                 'account_number' => $request->account_number,
                 'account_name' => $accountName,
                 'bank_name' => $bankName,
                 'is_primary' => auth()->user()->bankAccounts()->doesntExist(), // First one is primary
             ]);

             return redirect()->route('team.bank-accounts.index')->with('success', 'Bank account added successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['account_number' => 'Verification failed: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(TeamMemberBankAccount $bankAccount)
    {
        if ($bankAccount->user_id !== auth()->id()) {
            abort(403);
        }

        $bankAccount->delete();
        return back()->with('success', 'Bank account deleted.');
    }

    public function makePrimary(TeamMemberBankAccount $bankAccount)
    {
        if ($bankAccount->user_id !== auth()->id()) {
            abort(403);
        }

        auth()->user()->bankAccounts()->update(['is_primary' => false]);
        $bankAccount->update(['is_primary' => true]);

        return back()->with('success', 'Primary account updated.');
    }
}
