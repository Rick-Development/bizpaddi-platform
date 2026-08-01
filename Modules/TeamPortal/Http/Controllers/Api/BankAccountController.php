<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\TeamPortal\Models\TeamMemberBankAccount;
use Modules\Accounting\Services\SafeHavenService;

class BankAccountController extends ApiController
{
    protected SafeHavenService $safeHaven;

    public function __construct(SafeHavenService $safeHaven)
    {
        $this->safeHaven = $safeHaven;
    }

    /**
     * GET /api/v1/team/bank-accounts
     * List all bank accounts for the authenticated team member.
     */
    public function index(Request $request)
    {
        $accounts = TeamMemberBankAccount::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return $this->sendResponse($accounts, 'Bank accounts retrieved successfully.');
    }

    /**
     * GET /api/v1/team/bank-accounts/banks
     * Return list of banks from Safe Haven API.
     */
    public function banks(Request $request)
    {
        try {
            $rawBanks = $this->safeHaven->getBanks();

            // Normalise to a consistent {name, code} shape
            $banks = collect($rawBanks)
                ->map(fn($b) => [
                    'name' => $b['name'] ?? $b['bankName'] ?? 'Unknown',
                    'code' => $b['code'] ?? $b['bankCode'] ?? null,
                ])
                ->filter(fn($b) => !empty($b['code']))
                ->sortBy('name')
                ->values();

            return $this->sendResponse($banks, 'Bank list retrieved successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to fetch banks from Safe Haven: ' . $e->getMessage());
            return $this->sendError('Could not fetch bank list. Please try again.', 503);
        }
    }

    /**
     * POST /api/v1/team/bank-accounts
     * Add a new bank account (verifies account name via Safe Haven).
     */
    public function store(Request $request)
    {
        $request->validate([
            'bank_code'      => 'required|string|max:20',
            'account_number' => 'required|string|digits:10',
        ]);

        if (TeamMemberBankAccount::where('user_id', $request->user()->id)->exists()) {
            return $this->sendError('You can only have one bank account. Please update your existing account instead.', 400);
        }

        // Verify account name via Safe Haven
        try {
            $verification = $this->safeHaven->verifyAccount(
                $request->bank_code,
                $request->account_number
            );

            if (!($verification['success'] ?? false)) {
                return $this->sendError($verification['message'] ?? 'Account verification failed.', 422);
            }

            $accountName = $verification['data']['accountName'] ?? null;

            if (!$accountName) {
                return $this->sendError('Could not retrieve account name. Please check your details.', 422);
            }

            // Resolve bank name from code
            $rawBanks = $this->safeHaven->getBanks();
            $bankName = 'Unknown Bank';
            foreach ($rawBanks as $bank) {
                $code = $bank['code'] ?? $bank['bankCode'] ?? null;
                if ($code === $request->bank_code) {
                    $bankName = $bank['name'] ?? $bank['bankName'] ?? 'Unknown Bank';
                    break;
                }
            }

        } catch (\Exception $e) {
            Log::error('Safe Haven account verification error: ' . $e->getMessage());
            return $this->sendError('Account verification failed. Please try again.', 503);
        }

        $isPrimary = !TeamMemberBankAccount::where('user_id', $request->user()->id)->exists();

        // If there's already an account and user wants to set as primary
        if ($request->input('is_primary', false)) {
            TeamMemberBankAccount::where('user_id', $request->user()->id)->update(['is_primary' => false]);
            $isPrimary = true;
        }

        $account = TeamMemberBankAccount::create([
            'user_id'        => $request->user()->id,
            'bank_code'      => $request->bank_code,
            'account_number' => $request->account_number,
            'account_name'   => $accountName,
            'bank_name'      => $bankName,
            'is_primary'     => $isPrimary,
        ]);

        return $this->sendResponse($account, 'Bank account added successfully.', 201);
    }

    /**
     * POST /api/v1/team/bank-accounts/{id}/make-primary
     * Set a bank account as primary.
     */
    public function makePrimary(Request $request, $id)
    {
        $account = TeamMemberBankAccount::where('user_id', $request->user()->id)->find($id);

        if (!$account) {
            return $this->sendError('Bank account not found.', 404);
        }

        TeamMemberBankAccount::where('user_id', $request->user()->id)->update(['is_primary' => false]);
        $account->update(['is_primary' => true]);

        return $this->sendResponse($account, 'Primary bank account updated successfully.');
    }

    /**
     * PUT/PATCH /api/v1/team/bank-accounts/{id}
     * Update an existing bank account.
     */
    public function update(Request $request, $id)
    {
        $account = TeamMemberBankAccount::where('user_id', $request->user()->id)->find($id);

        if (!$account) {
            return $this->sendError('Bank account not found.', 404);
        }

        $request->validate([
            'bank_code'      => 'sometimes|string|max:20',
            'account_number' => 'sometimes|string|digits:10',
            'is_primary'     => 'sometimes|boolean',
        ]);

        // If account_number or bank_code changed, re-verify
        if ($request->has('account_number') || $request->has('bank_code')) {
            $bankCode      = $request->input('bank_code', $account->bank_code);
            $accountNumber = $request->input('account_number', $account->account_number);

            try {
                $verification = $this->safeHaven->verifyAccount($bankCode, $accountNumber);

                if (!($verification['success'] ?? false)) {
                    return $this->sendError($verification['message'] ?? 'Account verification failed.', 422);
                }

                $accountName = $verification['data']['accountName'] ?? $account->account_name;

                // Resolve bank name
                $rawBanks = $this->safeHaven->getBanks();
                $bankName = $account->bank_name;
                foreach ($rawBanks as $bank) {
                    $code = $bank['code'] ?? $bank['bankCode'] ?? null;
                    if ($code === $bankCode) {
                        $bankName = $bank['name'] ?? $bank['bankName'] ?? $account->bank_name;
                        break;
                    }
                }

                $account->bank_code      = $bankCode;
                $account->account_number = $accountNumber;
                $account->account_name   = $accountName;
                $account->bank_name      = $bankName;

            } catch (\Exception $e) {
                Log::error('Safe Haven account verification error: ' . $e->getMessage());
                return $this->sendError('Account verification failed. Please try again.', 503);
            }
        }

        if ($request->input('is_primary', false)) {
            TeamMemberBankAccount::where('user_id', $request->user()->id)->update(['is_primary' => false]);
            $account->is_primary = true;
        }

        $account->save();

        return $this->sendResponse($account, 'Bank account updated successfully.');
    }

    /**
     * DELETE /api/v1/team/bank-accounts/{id}
     * Remove a bank account.
     */
    public function destroy(Request $request, $id)
    {
        $account = TeamMemberBankAccount::where('user_id', $request->user()->id)->find($id);

        if (!$account) {
            return $this->sendError('Bank account not found.', 404);
        }

        $account->delete();

        return $this->sendResponse([], 'Bank account removed successfully.');
    }
}
