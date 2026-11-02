<?php

namespace App\Services\Billing;

use App\Models\SaasPlan;
use App\Models\Tenant;
use App\Models\TenantPayment;
use App\Models\TenantSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BillwayGateway
{
    protected string $baseUrl;
    protected ?string $apiKey;
    protected ?string $publicKey;
    protected ?string $secretKey;
    protected ?string $webhookSecret;
    protected string $defaultProvider;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.billway.base_url', 'https://billwayservices.com/api/v1'), '/');
        $this->publicKey = config('services.billway.public_key') ?: env('BILLWAY_PUBLIC_KEY');
        $this->secretKey = config('services.billway.secret_key') ?: env('BILLWAY_SECRET_KEY');
        $this->apiKey = config('services.billway.api_key', $this->publicKey);
        $this->webhookSecret = config('services.billway.webhook_secret');
        $this->defaultProvider = config('services.billway.default_provider', 'p4'); // p4 = 9PSB, p1 = Safe Haven
    }

    /**
     * Standard authentication headers required by Billway API.
     */
    protected function getHeaders(): array
    {
        return [
            'X-API-Key' => $this->publicKey ?: $this->apiKey,
            'X-API-Secret' => $this->secretKey ?: $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Create or retrieve a Sub-User on Billway for this business.
     */
    public function createSubUser(Tenant $tenant): array
    {
        if (empty($this->secretKey) && empty($this->apiKey)) {
            return ['success' => false, 'message' => 'Billway API credentials not configured.'];
        }

        try {
            $response = Http::withHeaders($this->getHeaders())->timeout(25)->post("{$this->baseUrl}/sub-users", [
                'email' => $tenant->email,
                'external_id' => 'tenant_' . $tenant->id,
                'name' => $tenant->name,
                'phone' => $tenant->phone ?: '08000000000',
                'metadata' => [
                    'tenant_id' => $tenant->id,
                    'tenant_slug' => $tenant->slug,
                    'app' => 'Bizpaddi',
                ],
            ]);

            if ($response->successful()) {
                $subUser = $response->json('data') ?? $response->json();
                $tenantSubUserId = $subUser['id'] ?? $subUser['sub_user_id'] ?? ('tenant_' . $tenant->id);

                // Save to tenant settings
                $settings = $tenant->settings ?? [];
                $settings['billway_sub_user_id'] = $tenantSubUserId;
                $tenant->update(['settings' => $settings]);

                return ['success' => true, 'sub_user' => $subUser, 'sub_user_id' => $tenantSubUserId];
            }

            Log::warning('Billway createSubUser failed or user exists', ['response' => $response->body()]);
            return ['success' => false, 'message' => $response->json('message') ?? 'Could not create sub-user'];
        } catch (\Exception $e) {
            Log::error('Billway createSubUser exception: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Generate a dedicated Billway Virtual Account for subscription collection.
     */
    public function createVirtualAccount(Tenant $tenant, float $amount, string $accountType = 'STATIC'): array
    {
        if (empty($this->secretKey) && empty($this->apiKey)) {
            return ['success' => false, 'message' => 'Billway API credentials not configured.'];
        }

        $cleanPhone = preg_replace('/[^0-9]/', '', $tenant->phone ?: '08030000000');
        if (strlen($cleanPhone) > 11 && str_starts_with($cleanPhone, '234')) {
            $cleanPhone = '0' . substr($cleanPhone, 3);
        }

        $subUserId = $tenant->settings['billway_sub_user_id'] ?? null;

        $payload = [
            'provider' => $this->defaultProvider,
            'amount' => max(100, (int) round($amount)),
            'email' => $tenant->email,
            'phone' => $cleanPhone ?: '08030000000',
            'name' => $tenant->name,
            'account_type' => $accountType, // STATIC or DYNAMIC
        ];

        if (!empty($subUserId)) {
            $payload['sub_user_id'] = (string) $subUserId;
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(25)
                ->post("{$this->baseUrl}/collections/virtual-account", $payload);

            if ($response->successful()) {
                $accountData = $response->json('data') ?? $response->json();
                $va = $accountData['virtual_account'] ?? $accountData;
                return [
                    'success' => true,
                    'account_number' => $va['account_number'] ?? $accountData['account_number'] ?? null,
                    'bank_name' => $va['bank_name'] ?? $accountData['bank_name'] ?? '9PSB',
                    'account_name' => $va['account_name'] ?? $accountData['account_name'] ?? $tenant->name,
                    'reference' => $accountData['reference'] ?? $accountData['session_id'] ?? ('BP_VA_' . uniqid()),
                    'amount' => $amount,
                    'raw' => $accountData,
                ];
            }

            Log::error('Billway virtual account creation failed', ['body' => $response->body()]);
            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Failed to generate virtual account on Billway.',
            ];
        } catch (\Exception $e) {
            Log::error('Billway createVirtualAccount exception: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Initialize subscription payment: creates a payment record & dedicated Virtual Account.
     */
    public function initializePayment(Tenant $tenant, SaasPlan $plan, int $seats = 1, ?string $callbackUrl = null): array
    {
        $amount = $plan->calculateTotal($seats);
        $reference = 'BP_SUB_' . strtoupper(uniqid()) . '_' . time();

        // 1. Create sub-user if not exists
        if (empty($tenant->settings['billway_sub_user_id'])) {
            $this->createSubUser($tenant);
            $tenant->refresh();
        }

        // 2. Create pending payment log
        $payment = TenantPayment::create([
            'tenant_id' => $tenant->id,
            'reference' => $reference,
            'amount' => $amount,
            'currency' => $tenant->currency ?: 'NGN',
            'gateway' => 'billway',
            'status' => 'pending',
            'metadata' => [
                'plan_id' => $plan->id,
                'plan_code' => $plan->code,
                'plan_name' => $plan->name,
                'seats' => $seats,
                'tenant_name' => $tenant->name,
                'tenant_email' => $tenant->email,
            ],
        ]);

        // 3. Generate Billway Virtual Account
        $vaResult = $this->createVirtualAccount($tenant, $amount, 'DYNAMIC');

        if ($vaResult['success']) {
            $payment->update([
                'gateway_reference' => $vaResult['reference'],
                'metadata' => array_merge($payment->metadata, [
                    'virtual_account' => $vaResult['account_number'],
                    'bank_name' => $vaResult['bank_name'],
                    'account_name' => $vaResult['account_name'],
                ]),
            ]);

            return [
                'success' => true,
                'reference' => $reference,
                'account_number' => $vaResult['account_number'],
                'bank_name' => $vaResult['bank_name'],
                'account_name' => $vaResult['account_name'],
                'amount' => $amount,
                'checkout_url' => route('admin.billing.pay', ['reference' => $reference]),
                'payment' => $payment,
            ];
        }

        return [
            'success' => false,
            'message' => $vaResult['message'] ?? 'Could not initialize payment with Billway.',
        ];
    }

    /**
     * Initialize operating wallet top-up via dedicated Billway Virtual Account.
     */
    public function initializeWalletTopup(Tenant $tenant, float $amount, ?string $callbackUrl = null): array
    {
        $amount = max(500, (float) $amount);
        $reference = 'BP_WLT_' . strtoupper(uniqid()) . '_' . time();

        $vaResult = $this->createVirtualAccount($tenant, $amount, 'DYNAMIC');

        if ($vaResult['success']) {
            $payment = TenantPayment::create([
                'tenant_id' => $tenant->id,
                'subscription_id' => null,
                'reference' => $reference,
                'amount' => $amount,
                'currency' => 'NGN',
                'gateway' => 'billway',
                'gateway_reference' => $vaResult['reference'],
                'status' => 'pending',
                'metadata' => [
                    'type' => 'wallet_topup',
                    'action' => 'topup',
                    'virtual_account' => $vaResult['account_number'],
                    'bank_name' => $vaResult['bank_name'],
                    'account_name' => $vaResult['account_name'],
                    'callback_url' => $callbackUrl,
                ],
            ]);

            return [
                'success' => true,
                'reference' => $reference,
                'account_number' => $vaResult['account_number'],
                'bank_name' => $vaResult['bank_name'],
                'account_name' => $vaResult['account_name'],
                'amount' => $amount,
                'checkout_url' => route('admin.billing.pay', ['reference' => $reference]),
                'payment' => $payment,
            ];
        }

        return [
            'success' => false,
            'message' => $vaResult['message'] ?? 'Could not initialize wallet top-up with Billway.',
        ];
    }

    /**
     * Check transaction payment status directly with Billway API.
     */
    public function checkRemoteTransactionStatus(TenantPayment $payment): bool
    {
        if (empty($this->secretKey) && empty($this->apiKey)) {
            return false;
        }

        $lookupRefs = array_filter(array_unique([
            $payment->gateway_reference,
            $payment->reference,
        ]));

        foreach ($lookupRefs as $ref) {
            try {
                $response = Http::withHeaders($this->getHeaders())
                    ->timeout(15)
                    ->get("{$this->baseUrl}/collections/verify/" . urlencode($ref));

                if ($response->successful()) {
                    $data = $response->json('data') ?? $response->json();
                    $status = strtolower((string) ($data['status'] ?? $data['payment_status'] ?? ''));
                    if (in_array($status, ['success', 'successful', 'completed', 'paid'], true)) {
                        return true;
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Billway verify check exception: ' . $e->getMessage());
            }
        }

        return false;
    }

    /**
     * Verify payment and activate/renew tenant subscription or credit wallet.
     */
    public function verifyAndActivate(string $reference, bool $trustedWebhook = false): array
    {
        $payment = TenantPayment::where('reference', $reference)
            ->orWhere('gateway_reference', $reference)
            ->first();

        if (!$payment) {
            return ['success' => false, 'message' => 'Payment reference not found.'];
        }

        if ($payment->status === 'successful') {
            return ['success' => true, 'message' => 'Payment already verified.', 'payment' => $payment];
        }

        // Unless triggered by a verified Billway webhook, confirm payment status against Billway API first
        if (!$trustedWebhook && !$this->checkRemoteTransactionStatus($payment)) {
            return [
                'success' => false,
                'message' => 'Payment has not yet been confirmed by Billway. Please allow a moment after transferring for bank settlement.',
            ];
        }

        // Mark payment successful
        $payment->update([
            'status' => 'successful',
            'paid_at' => Carbon::now(),
        ]);

        $metadata = $payment->metadata ?? [];

        // Handle operating wallet top-up
        if (($metadata['type'] ?? '') === 'wallet_topup') {
            $tenant = $payment->tenant;
            $tenant->creditWallet((float) $payment->amount, 'deposit', "Operating Wallet Top-up via Billway ({$reference})", $reference, $metadata);

            return [
                'success' => true,
                'message' => "Operating wallet credited with ₦" . number_format($payment->amount, 2) . " successfully!",
                'payment' => $payment,
            ];
        }

        $planId = $metadata['plan_id'] ?? null;
        $seats = $metadata['seats'] ?? 1;
        $plan = SaasPlan::find($planId);

        if (!$plan) {
            return ['success' => false, 'message' => 'Associated SaaS plan not found.'];
        }

        $tenant = $payment->tenant;
        $periodStart = Carbon::now();
        $periodEnd = $plan->billing_interval === 'yearly'
            ? $periodStart->copy()->addYear()
            : $periodStart->copy()->addMonth();

        // Create or update subscription
        $subscription = TenantSubscription::updateOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'plan_id' => $plan->id,
                'seats_purchased' => $seats,
                'amount_paid' => $payment->amount,
                'currency' => $payment->currency,
                'current_period_start' => $periodStart,
                'current_period_end' => $periodEnd,
                'status' => 'active',
                'payment_method' => 'billway',
                'last_payment_reference' => $payment->reference,
                'grace_period_ends_at' => null,
            ]
        );

        $payment->update(['subscription_id' => $subscription->id]);

        $tenant->update([
            'status' => 'active',
            'billing_model' => 'subscription',
            'trial_ends_at' => null,
            'ai_enabled' => (bool) $plan->enable_ai,
        ]);

        return [
            'success' => true,
            'message' => 'Subscription activated successfully!',
            'tenant' => $tenant,
            'subscription' => $subscription,
        ];
    }
}
