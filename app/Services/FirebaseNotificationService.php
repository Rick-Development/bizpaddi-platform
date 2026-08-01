<?php

namespace App\Services;

use Modules\Settings\Models\Setting;
use Modules\ClientPortal\Models\ClientDeviceToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseNotificationService
{
    private function getAccessToken()
    {
        $credentialsJson = Setting::get('fcm_server_key');
        
        if (empty($credentialsJson)) {
            throw new \Exception('FCM credentials not configured');
        }

        // Check if it's JSON (Service Account) or plain key (Legacy)
        $credentials = json_decode($credentialsJson, true);
        
        if (!$credentials) {
            // It's a legacy server key
            return $credentialsJson;
        }

        // Use Service Account to get access token
        try {
            $client = new \Google_Client();
            $client->setAuthConfig($credentials);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $token = $client->fetchAccessTokenWithAssertion();
            
            return $token['access_token'] ?? null;
        } catch (\Exception $e) {
            Log::error('Failed to get FCM access token: ' . $e->getMessage());
            throw $e;
        }
    }

    public function sendToClient($clientId, $title, $body, $data = [])
    {
        $tokens = ClientDeviceToken::where('client_id', $clientId)->pluck('fcm_token')->toArray();
        
        if (empty($tokens)) {
            return ['success' => false, 'message' => 'No device tokens found for client'];
        }

        return $this->sendToTokens($tokens, $title, $body, $data);
    }

    public function sendToClients(array $clientIds, $title, $body, $data = [])
    {
        $tokens = ClientDeviceToken::whereIn('client_id', $clientIds)->pluck('fcm_token')->toArray();
        
        if (empty($tokens)) {
            return ['success' => false, 'message' => 'No device tokens found'];
        }

        return $this->sendToTokens($tokens, $title, $body, $data);
    }

    public function sendToTokens(array $tokens, $title, $body, $data = [])
    {
        try {
            $accessToken = $this->getAccessToken();
            $credentialsJson = Setting::get('fcm_server_key');
            $credentials = json_decode($credentialsJson, true);
            
            if (!$credentials || !isset($credentials['project_id'])) {
                throw new \Exception('Invalid FCM credentials');
            }

            $projectId = $credentials['project_id'];
            $results = [];

            foreach ($tokens as $token) {
                $message = [
                    'message' => [
                        'token' => $token,
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                        'data' => $data,
                    ]
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ])->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", $message);

                $results[] = [
                    'token' => substr($token, 0, 20) . '...',
                    'success' => $response->successful(),
                    'response' => $response->json()
                ];
            }

            return [
                'success' => true,
                'results' => $results
            ];

        } catch (\Exception $e) {
            Log::error('FCM Send Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
