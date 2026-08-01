<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Accounting\Services\SafeHavenService;
use Modules\Settings\Models\Setting;

class TeamUtilityController extends Controller
{
    protected $safeHavenService;

    public function __construct(SafeHavenService $safeHavenService)
    {
        $this->safeHavenService = $safeHavenService;
    }

    public function index()
    {
        $teamMembers = User::orderBy('name')->get();
        
        // Fetch VAS Services (Airtime, Data, etc.)
        $response = $this->safeHavenService->getServices();
        $services = $response['data'] ?? [];

        // Filter for Airtime and Data identifiers
        $utilityServices = array_filter($services, function($service) {
            return in_array($service['identifier'], ['AIRTIME', 'DATA']);
        });

        return view('admin::utility.index', compact('teamMembers', 'utilityServices'));
    }

    public function updatePhones(Request $request, User $user)
    {
        $validated = $request->validate([
            'airtime_phone' => 'nullable|string|max:15',
            'data_phone' => 'nullable|string|max:15',
        ]);

        $user->update($validated);

        return response()->json(['success' => true, 'message' => 'Phone numbers updated successfully.']);
    }

    public function fetchServiceCategories(Request $request, $serviceId)
    {
        $response = $this->safeHavenService->getServiceCategories($serviceId);
        return response()->json($response);
    }

    public function fetchCategoryProducts(Request $request, $categoryId)
    {
        $response = $this->safeHavenService->getCategoryProducts($categoryId);
        return response()->json($response);
    }

    public function purchase(Request $request)
    {
        \Log::info('Team Utility Purchase Request:', $request->all());

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:AIRTIME,DATA',
            'service_category_id' => 'required|string',
            'amount' => 'nullable|required_if:type,AIRTIME|numeric|min:1',
            'bundle_code' => 'nullable|required_if:type,DATA|string',
            'data_amount' => 'nullable|required_if:type,DATA|numeric', 
            'phone_number' => 'required|string',
        ]);

        $user = User::findOrFail($request->user_id);
        $user = User::findOrFail($request->user_id);
        $debitAccount = Setting::get('safehaven_settlement_account_number');

        if (!$debitAccount) {
            \Log::error('Settlement account not configured');
            return back()->with('error', 'Settlement account not configured. Please contact administrator.');
        }

        $amount = $request->type === 'AIRTIME' ? $request->amount : $request->data_amount;
        $description = $request->type === 'AIRTIME' 
            ? "Airtime recharge for {$user->name} - {$request->phone_number}" 
            : "Data bundle for {$user->name} - {$request->phone_number}";

        try {
            if ($request->type === 'AIRTIME') {
                $payload = [
                    'amount' => (float) $request->amount,
                    'phoneNumber' => $request->phone_number,
                    'serviceCategoryId' => $request->service_category_id,
                    'debitAccountNumber' => $debitAccount,
                ];
                \Log::info('Purchasing Airtime:', $payload);
                $response = $this->safeHavenService->purchaseAirtime($payload);
            } else {
                $payload = [
                    'bundleCode' => $request->bundle_code,
                    'amount' => (float) $request->data_amount,
                    'phoneNumber' => $request->phone_number,
                    'serviceCategoryId' => $request->service_category_id,
                    'debitAccountNumber' => $debitAccount,
                ];
                \Log::info('Purchasing Data:', $payload);
                $response = $this->safeHavenService->purchaseData($payload);
            }

            \Log::info('SafeHaven VAS Response:', $response);

            // Create transaction record
            $transaction = \Modules\Accounting\Models\Transaction::create([
                'transaction_number' => 'VAS-' . strtoupper(uniqid()),
                'transaction_date' => now(),
                'payable_type' => User::class,
                'payable_id' => $user->id,
                'reference' => $response['reference'] ?? null,
                'amount' => $amount,
                'currency_code' => 'NGN',
                'status' => (isset($response['statusCode']) && $response['statusCode'] == 200) ? 'completed' : 'failed',
                'type' => 'debit',
                'source' => 'team_utility',
                'provider' => 'SafeHaven',
                'provider_reference' => $response['data']['reference'] ?? $response['reference'] ?? null,
                'description' => $description,
                'metadata' => [
                    'service_type' => $request->type,
                    'phone_number' => $request->phone_number,
                    'bundle_code' => $request->bundle_code ?? null,
                    'service_category_id' => $request->service_category_id,
                    'response' => $response,
                ],
                'paid_at' => (isset($response['statusCode']) && $response['statusCode'] == 200) ? now() : null,
            ]);

            if (isset($response['statusCode']) && $response['statusCode'] == 200) {
                \Log::info('Purchase successful', ['transaction_id' => $transaction->id]);
                return redirect()->route('admin.team-utilities.success', $transaction);
            } else {
                \Log::warning('Purchase failed', ['response' => $response, 'transaction_id' => $transaction->id]);
                return back()->with('error', $response['message'] ?? 'Purchase failed. Please try again.');
            }

        } catch (\Exception $e) {
            \Log::error('Purchase exception:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Create failed transaction record
            try {
                \Modules\Accounting\Models\Transaction::create([
                    'transaction_number' => 'VAS-' . strtoupper(uniqid()),
                    'transaction_date' => now(),
                    'payable_type' => User::class,
                    'payable_id' => $user->id,
                    'amount' => $amount,
                    'currency_code' => 'NGN',
                    'status' => 'failed',
                    'type' => 'debit',
                    'source' => 'team_utility',
                    'provider' => 'SafeHaven',
                    'description' => $description,
                    'metadata' => [
                        'service_type' => $request->type,
                        'phone_number' => $request->phone_number,
                        'error' => $e->getMessage(),
                    ],
                ]);
            } catch (\Exception $logEx) {
                \Log::error('Failed to log transaction:', ['error' => $logEx->getMessage()]);
            }

            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function success(\Modules\Accounting\Models\Transaction $transaction)
    {
        return view('admin::utility.success', compact('transaction'));
    }
}
