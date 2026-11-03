<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SaasPlan;
use App\Models\Tenant;
use App\Models\TenantPayment;
use App\Services\Billing\BillwayGateway;
use Illuminate\Http\Request;

class BusinessBillingController extends Controller
{
    /**
     * Resolve the current active tenant ID respecting admin guard and impersonation.
     */
    protected function getTenantId(): int
    {
        if (session('impersonated_tenant_id')) {
            return (int) session('impersonated_tenant_id');
        }
        if (auth('admin')->check() && auth('admin')->user()->tenant_id) {
            return (int) auth('admin')->user()->tenant_id;
        }
        if (auth()->check() && auth()->user()->tenant_id) {
            return (int) auth()->user()->tenant_id;
        }
        return 1;
    }

    /**
     * Show the business tenant's current plan and billing overview.
     */
    public function index()
    {
        $tenantId = $this->getTenantId();

        $tenant = Tenant::with(['activeSubscription.plan', 'users', 'payments'])->find($tenantId);

        if (!$tenant) {
            $tenant = Tenant::first();
        }

        $subscription = $tenant->activeSubscription;
        $activeSeats = $tenant->users()->count();
        $availablePlans = SaasPlan::active()->where('billing_type', '!=', 'payg')->get();
        $recentPayments = TenantPayment::where('tenant_id', $tenant->id)->latest()->take(10)->get();
        $walletTransactions = $tenant->walletTransactions()->take(15)->get();
        $dynamicRates = \App\Models\PlatformFeatureRate::where('is_active', true)->orderBy('category')->orderBy('fee', 'desc')->get();

        return view('admin::billing.index', compact(
            'tenant',
            'subscription',
            'activeSeats',
            'availablePlans',
            'recentPayments',
            'walletTransactions',
            'dynamicRates'
        ));
    }

    /**
     * Top up business operational wallet via Billway.
     */
    public function topupWallet(Request $request, BillwayGateway $gateway)
    {
        $request->validate([
            'amount' => 'required|numeric|min:500',
        ]);

        $tenantId = $this->getTenantId();

        $tenant = Tenant::findOrFail($tenantId);
        $amount = (float) $request->amount;

        $result = $gateway->initializeWalletTopup(
            $tenant,
            $amount,
            route('admin.billing.callback')
        );

        if ($result['success'] && !empty($result['checkout_url'])) {
            return redirect($result['checkout_url']);
        }

        return back()->with('error', $result['message'] ?? 'Could not initialize wallet top-up with Billway.');
    }

    /**
     * Switch business billing mode between Subscription and Pay-As-You-Transact.
     */
    public function switchModel(Request $request)
    {
        $request->validate([
            'billing_model' => 'required|in:subscription,payg',
        ]);

        $tenantId = $this->getTenantId();

        $tenant = Tenant::findOrFail($tenantId);

        if ($request->billing_model === 'subscription') {
            $activeSub = $tenant->activeSubscription;
            if (!$activeSub || !$activeSub->isValid()) {
                return back()->with('error', 'Please select and activate a monthly or annual subscription plan below first.');
            }
        }

        $tenant->update(['billing_model' => $request->billing_model]);

        $modelName = $request->billing_model === 'payg' ? 'Pay-As-You-Transact (Micro-Fees)' : 'Unlimited Monthly Subscription';

        return back()->with('success', "Billing mode successfully changed to {$modelName}.");
    }

    /**
     * Initialize Billway checkout for plan renewal or upgrade.
     */
    public function renewOrUpgrade(Request $request, BillwayGateway $gateway)
    {
        $request->validate([
            'plan_id' => 'required|exists:saas_plans,id',
            'seats' => 'nullable|integer|min:1',
        ]);

        $tenantId = $this->getTenantId();

        $tenant = Tenant::findOrFail($tenantId);
        $plan = SaasPlan::findOrFail($request->plan_id);
        $seats = (int) ($request->seats ?? $plan->included_users);

        $result = $gateway->initializePayment(
            $tenant,
            $plan,
            $seats,
            route('admin.billing.callback')
        );

        if ($result['success'] && !empty($result['checkout_url'])) {
            return redirect($result['checkout_url']);
        }

        return back()->with('error', $result['message'] ?? 'Could not initialize payment with Billway.');
    }

    /**
     * Show dedicated Billway Virtual Account transfer page.
     */
    public function pay(Request $request)
    {
        $reference = $request->get('reference');
        $payment = TenantPayment::with('tenant')->where('reference', $reference)->firstOrFail();

        if ($payment->status === 'successful') {
            return redirect()->route('admin.billing.index')->with('success', 'Payment already verified and active.');
        }

        $vaNumber = $payment->metadata['virtual_account'] ?? null;
        $bankName = $payment->metadata['bank_name'] ?? '9PSB / Safe Haven';
        $accountName = $payment->metadata['account_name'] ?? $payment->tenant->name;

        return view('admin::billing.pay', compact('payment', 'vaNumber', 'bankName', 'accountName'));
    }

    /**
     * AJAX status check for background auto-verification.
     */
    public function checkStatus(Request $request, BillwayGateway $gateway)
    {
        $reference = $request->get('reference');
        if (!$reference) {
            return response()->json(['success' => false, 'status' => 'missing_reference', 'message' => 'No reference provided.'], 400);
        }

        $payment = TenantPayment::where('reference', $reference)
            ->orWhere('gateway_reference', $reference)
            ->first();

        if (!$payment) {
            return response()->json(['success' => false, 'status' => 'not_found', 'message' => 'Payment record not found.'], 404);
        }

        // If already marked successful (e.g. via Billway webhook)
        if ($payment->status === 'successful') {
            return response()->json([
                'success' => true,
                'status' => 'successful',
                'message' => 'Payment verified successfully! Updating your workspace...',
                'redirect_url' => route('admin.billing.index'),
            ]);
        }

        // Active verify check against Billway API
        $result = $gateway->verifyAndActivate($reference);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'status' => 'successful',
                'message' => $result['message'] ?? 'Payment verified successfully! Updating your workspace...',
                'redirect_url' => route('admin.billing.index'),
            ]);
        }

        return response()->json([
            'success' => false,
            'status' => 'pending',
            'message' => 'Waiting for bank transfer settlement...',
        ]);
    }

    /**
     * Instant verification check.
     */
    public function verifyNow(Request $request, BillwayGateway $gateway)
    {
        $reference = $request->get('reference');
        $result = $gateway->verifyAndActivate($reference);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => $result['success'],
                'status' => $result['success'] ? 'successful' : 'pending',
                'message' => $result['message'] ?? ($result['success'] ? 'Payment verified successfully!' : 'Payment still pending bank settlement.'),
                'redirect_url' => route('admin.billing.index'),
            ]);
        }

        if ($result['success']) {
            return redirect()->route('admin.billing.index')
                ->with('success', $result['message'] ?? 'Payment verified! Your subscription is now active.');
        }

        return back()->with('info', 'Payment is still processing or waiting for bank settlement. Please give it a few moments.');
    }

    /**
     * Callback handler when returning from Billway checkout.
     */
    public function callback(Request $request, BillwayGateway $gateway)
    {
        $reference = $request->get('reference') ?? $request->get('trxref');

        if (!$reference) {
            return redirect()->route('admin.billing.index')->with('error', 'No payment reference returned.');
        }

        $result = $gateway->verifyAndActivate($reference);

        if ($result['success']) {
            return redirect()->route('admin.billing.index')
                ->with('success', 'Subscription updated and active! Thank you for choosing Bizpaddi.');
        }

        return redirect()->route('admin.billing.index')
            ->with('error', $result['message'] ?? 'Subscription verification failed.');
    }
}
