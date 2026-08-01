<?php

namespace Modules\ClientPortal\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\ClientPortal\Models\Payment;
use Modules\ClientPortal\Models\Invoice;

/**
 * SafeHavenPaymentService - Integration with Safe Haven Microfinance Bank
 * 
 * This service handles:
 * - Payment initialization
 * - Transaction verification
 * - Webhook handling
 * - Payment status updates
 */
class SafeHavenPaymentService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $apiSecret;

    public function __construct()
    {
        $this->baseUrl = config('services.safe_haven.base_url');
        $this->apiKey = config('services.safe_haven.api_key');
        $this->apiSecret = config('services.safe_haven.api_secret');
    }

    /**
     * Initialize a payment.
     *
     * @param Invoice $invoice
     * @param array $params
     * @return array
     */
    public function initiatePayment(Invoice $invoice, array $params = []): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/payments/initialize", [
                'amount' => $invoice->total,
                'currency' => $params['currency'] ?? 'NGN',
                'reference' => $this->generateReference($invoice),
                'customer' => [
                    'email' => $invoice->user->email,
                    'name' => $invoice->user->name,
                ],
                'metadata' => [
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'project_id' => $invoice->project_id,
                ],
                'callback_url' => route('client.payment.callback'),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Create payment record
                $payment = Payment::create([
                    'invoice_id' => $invoice->id,
                    'user_id' => $invoice->user_id,
                    'amount' => $invoice->total,
                    'payment_method' => 'safe_haven_bank',
                    'reference' => $data['data']['reference'] ?? null,
                    'transaction_id' => $data['data']['transaction_id'] ?? null,
                    'status' => 'pending',
                    'metadata' => $data,
                ]);

                return [
                    'success' => true,
                    'payment' => $payment,
                    'authorization_url' => $data['data']['authorization_url'] ?? null,
                    'reference' => $data['data']['reference'] ?? null,
                ];
            }

            Log::error('Safe Haven payment initialization failed', [
                'response' => $response->body(),
                'invoice_id' => $invoice->id,
            ]);

            return [
                'success' => false,
                'message' => 'Payment initialization failed',
                'error' => $response->json('message') ?? 'Unknown error',
            ];
        } catch (\Exception $e) {
            Log::error('Safe Haven payment exception', [
                'error' => $e->getMessage(),
                'invoice_id' => $invoice->id,
            ]);

            return [
                'success' => false,
                'message' => 'Payment service unavailable',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify a payment.
     *
     * @param string $reference
     * @return array
     */
    public function verifyPayment(string $reference): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get("{$this->baseUrl}/payments/verify/{$reference}");

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'status' => $data['data']['status'] ?? 'unknown',
                    'data' => $data['data'] ?? [],
                ];
            }

            return [
                'success' => false,
                'message' => 'Verification failed',
            ];
        } catch (\Exception $e) {
            Log::error('Payment verification failed', [
                'error' => $e->getMessage(),
                'reference' => $reference,
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle payment webhook.
     *
     * @param array $payload
     * @return bool
     */
    public function handleWebhook(array $payload): bool
    {
        try {
            // Verify webhook signature
            if (!$this->verifyWebhookSignature($payload)) {
                Log::warning('Invalid webhook signature');
                return false;
            }

            $reference = $payload['data']['reference'] ?? null;
            if (!$reference) {
                return false;
            }

            $payment = Payment::where('reference', $reference)->first();
            if (!$payment) {
                Log::warning('Payment not found for webhook', ['reference' => $reference]);
                return false;
            }

            // Update payment status
            $status = $this->mapStatus($payload['data']['status'] ?? 'unknown');
            $payment->update([
                'status' => $status,
                'transaction_id' => $payload['data']['transaction_id'] ?? $payment->transaction_id,
                'metadata' => array_merge($payment->metadata ?? [], $payload),
                'processed_at' => $status === 'completed' ? now() : null,
            ]);

            // Update invoice if payment completed
            if ($status === 'completed') {
                $payment->invoice->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Webhook handling failed', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);
            return false;
        }
    }

    /**
     * Generate unique payment reference.
     *
     * @param Invoice $invoice
     * @return string
     */
    protected function generateReference(Invoice $invoice): string
    {
        return 'SH-' . $invoice->invoice_number . '-' . time();
    }

    /**
     * Verify webhook signature.
     *
     * @param array $payload
     * @return bool
     */
    protected function verifyWebhookSignature(array $payload): bool
    {
        // Implement Safe Haven's webhook signature verification
        // This is a placeholder - adjust based on actual Safe Haven API documentation
        $signature = request()->header('X-Safe-Haven-Signature');
        if (!$signature) {
            return false;
        }

        $computedSignature = hash_hmac('sha256', json_encode($payload), $this->apiSecret);
        return hash_equals($computedSignature, $signature);
    }

    /**
     * Map Safe Haven status to internal status.
     *
     * @param string $externalStatus
     * @return string
     */
    protected function mapStatus(string $externalStatus): string
    {
        return match(strtolower($externalStatus)) {
            'success', 'successful', 'completed' => 'completed',
            'pending', 'processing' => 'processing',
            'failed', 'declined' => 'failed',
            'refunded' => 'refunded',
            default => 'pending',
        };
    }

    /**
     * Get transaction details.
     *
     * @param string $transactionId
     * @return array
     */
    public function getTransaction(string $transactionId): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get("{$this->baseUrl}/transactions/{$transactionId}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data'),
                ];
            }

            return ['success' => false];
        } catch (\Exception $e) {
            Log::error('Get transaction failed', ['error' => $e->getMessage()]);
            return ['success' => false];
        }
    }
}
