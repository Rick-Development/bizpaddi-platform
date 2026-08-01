<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $apiKey;
    protected $senderId;
    protected $baseUrl;
    protected $channel;

    public function __construct()
    {
        $this->apiKey = config('services.termii.api_key');
        $this->senderId = config('services.termii.sender_id');
        $this->baseUrl = config('services.termii.base_url');
        $this->channel = config('services.termii.channel', 'generic');
    }

    /**
     * Send SMS via Termii
     *
     * @param string $to
     * @param string $message
     * @return array
     */
    public function send($to, $message)
    {
        // Ensure phone number has country code for Termii
        $to = $this->formatPhoneNumber($to);

        $payload = [
            'api_key' => $this->apiKey,
            'to' => $to,
            'from' => $this->senderId,
            'sms' => $message,
            'type' => 'plain',
            'channel' => $this->channel,
        ];

        try {
            $response = Http::post($this->baseUrl . 'sms/send', $payload);

            if ($response->successful()) {
                Log::info("SMS successfully sent to {$to} via Termii.");
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::error("Termii SMS failed for {$to}: " . $response->body());
            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Failed to send SMS',
            ];
        } catch (\Exception $e) {
            Log::error("Termii SMS exception for {$to}: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Format phone number to international format without +
     *
     * @param string $phone
     * @return string
     */
    protected function formatPhoneNumber($phone)
    {
        // Remove non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If it starts with 0 and has 11 digits (Nigerian format), replace 0 with 234
        if (strlen($phone) == 11 && $phone[0] == '0') {
            $phone = '234' . substr($phone, 1);
        }

        return $phone;
    }
}
