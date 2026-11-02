<?php

namespace Modules\Accounting\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SafeHavenService
{
    protected ?string $clientId = null;
    protected ?string $clientAssertion = null;
    protected ?string $apiUrl = null;
    protected string $cacheKey = 'safehaven_auth_token';

    public function __construct()
    {
        $this->clientId        = config('services.safehaven.client_id');
        $this->clientAssertion = config('services.safehaven.client_assertion');
        $this->apiUrl          = rtrim(config('services.safehaven.url') ?? '', '/');
    }


    /**
     * Get Access Token (cached)
     */
    protected function authenticate(): array
    {
        if (empty($this->clientId) || empty($this->clientAssertion) || empty($this->apiUrl)) {
            Log::warning('SafeHaven credentials are not configured in environment.');
            throw new \Exception('SafeHaven credentials are not configured.');
        }

        $tokenData = Cache::get($this->cacheKey);

        if ($tokenData) {
            $createdAt = $tokenData['created_at'];
            $expiresIn = $tokenData['expires_in'];
            $expiresAt = $createdAt + $expiresIn;

            if (now()->timestamp < $expiresAt - 60) {
                return $tokenData;
            }
        }

        $credentials = [
            'grant_type'             => 'client_credentials',
            'client_id'              => $this->clientId,
            'client_assertion_type'  => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
            'client_assertion'       => $this->clientAssertion,
        ];

        $jsonData = json_encode($credentials);
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->apiUrl . '/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $jsonData,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new \Exception("SafeHeaven Auth Error: {$err}");
        }

        $decoded = json_decode($response, true);

        if (!isset($decoded['access_token'])) {
            Log::error('SafeHeaven Auth Failed', ['response' => $response]);
            throw new \Exception("Failed to obtain access token: " . $response);
        }

        $token = [
            'access_token' => $decoded['access_token'],
            'expires_in'   => $decoded['expires_in'],
            'ibs_client_id'=> $decoded['ibs_client_id'],
            'created_at'   => now()->timestamp,
        ];

        Cache::put($this->cacheKey, $token, $decoded['expires_in'] - 60);
        return $token;
    }

    /**
     * Perform GET request
     */
    protected function get(string $endpoint): array
    {
        try {
            $auth = $this->authenticate();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("SafeHaven Auth Error: " . $e->getMessage());
            return [];
        }

        $curl = curl_init();
        $url = $this->apiUrl . $endpoint;

        \Illuminate\Support\Facades\Log::info("SafeHaven GET Request: $url");

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer {$auth['access_token']}",
                "ClientID: {$auth['ibs_client_id']}",
                "Accept: application/json",
            ],
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            \Illuminate\Support\Facades\Log::error("SafeHaven cURL Error: " . $err);
            return [];
        }

        \Illuminate\Support\Facades\Log::info("SafeHaven Response ($httpCode): " . substr($response, 0, 500));

        return json_decode($response, true) ?? [];
    }

    /**
     * Perform POST request
     */
    protected function post(string $endpoint, array $data): array
    {
        $auth = $this->authenticate();
        $jsonData = json_encode($data);
        $url = $this->apiUrl . $endpoint;

        // Log::info('SafeHeaven POST', ['url' => $url, 'data' => $data]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 60, // Increased timeout
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $jsonData,
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer {$auth['access_token']}",
                "ClientID: {$auth['ibs_client_id']}",
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData)
            ],
        ]);

        \Log::info("SafeHaven POST Request: {$url}", ['payload' => $data]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        
        curl_close($curl);

        if ($err) {
            \Log::error("SafeHaven cURL Error: {$err}");
            throw new \Exception("SafeHaven Request Failed: {$err}");
        }

        \Log::info("SafeHaven POST Response ({$httpCode}): " . substr($response, 0, 1000)); // Log first 1000 chars

        $decodedResponse = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
             \Log::error("SafeHaven JSON Decode Error: " . json_last_error_msg());
             return ['statusCode' => $httpCode, 'message' => 'Invalid JSON response from SafeHaven', 'raw_response' => $response];
        }

        return $decodedResponse ?? [];
    }



    // ======================================================
    // KYC METHODS
    // ======================================================

    public function initiateVerification($data){
        return $this->post('/identity/v2',$data);
    }

    public function validateIdentity($data){
         return $this->post('/identity/v2/validate ',$data);
    }

    /**
 * Validate BVN with OTP
 * Returns verified BVN data
 */
public function validateBvnVerification(string $identityId, string $otp, string $type = 'BVN'): array
{
    $response = $this->post('/identity/v2/validate', [
        'type' => $type,
        'async' => true,
        'identityId' => $identityId,
        'otp' => $otp,
    ]);

    // Log::info('SafeHaven BVN Validate', ['response' => $response]);

    return $response;
}

/**
 * Initiate NIN Verification
 */
public function initiateNinVerification(string $nin, string $debitAccountNumber): array
{
    $response = $this->post('/identity/v2', [
        'type' => 'NIN',
        'async' => true,
        'number' => $nin,
        'debitAccountNumber' => $debitAccountNumber,
    ]);

    // Log::info('SafeHaven NIN Initiate', ['response' => $response]);

    return $response;
}

/**
 * Initiate vBVN (USSD) Verification
 * Customer dials *565*3# and enters BVN + iGree Code (11113)
 */
public function initiateBvnUssdVerification(string $bvn, string $otp, string $debitAccountNumber): array
{
    $response = $this->post('/identity/v2', [
        'type' => 'BVNUSSD',
        'number' => $bvn,
        'otp' => $otp,
        'debitAccountNumber' => $debitAccountNumber,
    ]);

    // Log::info('SafeHaven vBVN Initiate', ['response' => $response]);

    return $response;
}

/**
 * Initiate CAC Verification
 */
public function initiateCacVerification(string $cacNumber, string $debitAccountNumber): array
{
    $response = $this->post('/identity/v2', [
        'type' => 'CAC',
        'async' => true,
        'number' => $cacNumber,
        'debitAccountNumber' => $debitAccountNumber,
    ]);

    // Log::info('SafeHaven CAC Initiate', ['response' => $response]);

    return $response;
}

/**
 * Initiate BVN Verification
 * This sends OTP to the phone number registered with the BVN
 */
public function initiateBvnVerification(string $bvn, string $debitAccountNumber): array
{
    $response = $this->post('/identity/v2', [
        'type' => 'BVN',
        'async' => false,
        'number' => $bvn,
        'debitAccountNumber' => $debitAccountNumber,
    ]);

        // Log::info('SafeHaven BVN Initiate', ['response' => $response]);

    return $response;
}


    // ======================================================
    // BUSINESS METHODS
    // ======================================================

    //Create Sub Account (Corporate)

    public function createCorporateAccount($data){
        return $this->post('/accounts/v2/subaccount/',$data);
    }

    public function createIndividualAccount($data){
        return $this->post('/accounts/v2/subaccount/',$data);
    }




    public function initiateCollection(array $payload): array
    {
        $data = [
            'validFor' => $payload['valid_for'] ?? 3600, 
            'callbackUrl' => $payload['callback_url'] ?? url('/'),
            'settlementAccount' => [
                'bankCode' => $payload['settlement_bank_code'],
                'accountNumber' => $payload['settlement_account_number']
            ],
            'amountControl' => 'Fixed',
            'amount' => (float)$payload['amount'],
            'externalReference' => $payload['reference'],
        ];

        $response = $this->post('/virtual-accounts', $data);

        return isset($response['data'])
            ? [
                'success' => true,
                'data' => [
                    'account_number' => $response['data']['accountNumber'],
                    'account_name'   => $response['data']['accountName'],
                    'bank_name'      => $response['data']['bankName'] ?? 'Safe Haven MFB',
                    'reference'      => $payload['reference'],
                    'session_id'     => $response['data']['_id'] ?? null,
                ],
            ]
            : ['success' => false, 'message' => $response['description'] ?? $response['message'] ?? 'Virtual account creation failed'];
    }

    public function verifyTransaction(string $reference): array
    {
        $response = $this->get("/transactions/{$reference}");

        return isset($response['data'])
            ? [
                'success' => true,
                'data' => [
                    'reference' => $response['data']['reference'],
                    'status'    => $response['data']['status'],
                    'amount'    => $response['data']['amount'],
                    'paid_at'   => $response['data']['paidAt'] ?? null,
                ],
            ]
            : ['success' => false, 'message' => $response['description'] ?? 'Transaction not found'];
    }

    public function checkVirtualAccountStatus(string $sessionId): array
    {
        $response = $this->get("/virtual-accounts/{$sessionId}/transaction");

        return isset($response['data'])
            ? [
                'success' => true,
                'data' => [
                    'status' => $response['data']['status'] ?? 'pending',
                    'amount' => $response['data']['amount'] ?? null,
                    'paid_at' => $response['data']['paidAt'] ?? $response['data']['createdAt'] ?? null,
                    'transactions' => $response['data'],
                ],
            ]
            : ['success' => false, 'message' => $response['message'] ?? 'Unable to check payment status'];
    }

    public function processDisbursement(array $payload): array
    {
        $requestData = [
            'amount'                   => (float)$payload['amount'],
            'beneficiaryBankCode'      => $payload['bank_code'],
            'beneficiaryAccountNumber' => $payload['account_number'],
            'narration'                => $payload['narration'] ?? 'Transfer',
            'nameEnquiryReference'     => $payload['name_enquiry_reference'],
            'debitAccountNumber'       => $payload['debit_account'],
            'paymentReference'        => Str::random(16),
            'saveBeneficiary'          => false
        ];

        if (!empty($payload['account_name'])) {
            $requestData['beneficiaryName'] = $payload['account_name'];
        }

        $response = $this->post('/transfers', $requestData);

        return isset($response['data'])
            ? [
                'success' => true,
                'data' => [
                    'reference'  => $response['data']['reference'] ?? ($response['data']['paymentReference'] ?? null),
                    'status'     => $response['data']['status'] ?? 'pending',
                    'session_id' => $response['data']['sessionId'] ?? null,
                ],
            ]
            : ['success' => false, 'message' => $response['description'] ?? ($response['message'] ?? 'Transfer failed')];
    }

    public function verifyAccount(string $bankCode, string $accountNumber): array
    {
       $response = $this->post("/transfers/name-enquiry", [
        'bankCode' => $bankCode,
        'accountNumber' => $accountNumber
       ]);

       return (isset($response['statusCode']) && $response['statusCode'] == 200)
            ? ['success' => true, 'data' => $response['data']]
            : ['success' => false, 'message' => $response['message'] ?? 'Account verification failed'];
    }

    public function getBanks(): array
    {
        $response = $this->get('/banks');

        // $banks = $response['data'];
        return $response['data'] ?? [];
    }




    public function getAccount(string $accountNumber): array
    {
        // First, get all main accounts to find the one matching our account number
        $response = $this->get("/accounts?isSubAccount=false&limit=100");
        
        if (!isset($response['data']) || !is_array($response['data'])) {
            return ['success' => false, 'message' => $response['message'] ?? 'Unable to fetch accounts'];
        }

        // Find the account with matching account number
        $account = null;
        foreach ($response['data'] as $acc) {
            if (isset($acc['accountNumber']) && $acc['accountNumber'] === $accountNumber) {
                $account = $acc;
                break;
            }
        }

        if (!$account) {
            return ['success' => false, 'message' => "Account number {$accountNumber} not found"];
        }

        // Now get the full account details using the _id
        if (isset($account['_id'])) {
            $detailResponse = $this->get("/accounts/{$account['_id']}");
            if (isset($detailResponse['data'])) {
                return ['success' => true, 'data' => $detailResponse['data']];
            }
        }

        // If we can't get details, return what we have from the list
        return ['success' => true, 'data' => $account];
    }

    public function getTransactions(array $params = []): array
    {
        // Get account number from params or settings
        $accountNumber = $params['account_number'] ?? \Modules\Settings\Models\Setting::get('safehaven_settlement_account_number');
        
        if (!$accountNumber) {
            return ['success' => false, 'message' => 'Account number is required', 'data' => []];
        }

        // Get the account details (which includes the _id)
        $accountResponse = $this->getAccount($accountNumber);
        
        if (!$accountResponse['success']) {
            return ['success' => false, 'message' => $accountResponse['message'], 'data' => []];
        }

        $accountId = $accountResponse['data']['_id'] ?? null;
        
        if (!$accountId) {
            return ['success' => false, 'message' => 'Account ID not found', 'data' => []];
        }

        // Fetch account statement (transactions)
        $limit = $params['limit'] ?? 25;
        $page = $params['page'] ?? 0; // SafeHaven uses 0-based pagination
        
        // Build query parameters
        $queryParams = [
            'limit' => $limit,
            'page' => $page
        ];
        
        // Add optional date filters if provided
        if (isset($params['fromDate'])) {
            $queryParams['fromDate'] = $params['fromDate'];
        }
        if (isset($params['toDate'])) {
            $queryParams['toDate'] = $params['toDate'];
        }
        if (isset($params['type'])) { // 'Credit' or 'Debit'
            $queryParams['type'] = $params['type'];
        }
        
        $queryString = http_build_query($queryParams);
        $response = $this->get("/accounts/{$accountId}/statement?{$queryString}");

        if (isset($response['data'])) {
            return [
                'success' => true, 
                'data' => $response['data'],
                'meta' => $response['pagination'] ?? []
            ];
        }

        return [
            'success' => false, 
            'message' => $response['message'] ?? 'Unable to fetch account statement',
            'data' => []
        ];
    }

    // ======================================================
    // BILL PAYMENTS METHODS
    // ======================================================


    public function getServices(){
        return $this->get('/vas/services');
    }

    public function getService($id){
        return $this->get("/vas/service/$id");
    }

    public function getServiceCategories($serviceId){
        return $this->get("/vas/service/{$serviceId}/service-categories");
    }

    public function getCategoryProducts($categoryId){
        $response = $this->get("/vas/service-category/{$categoryId}/products");
        \Illuminate\Support\Facades\Log::info('SafeHaven Data Bundles:', ['categoryId' => $categoryId, 'response' => $response]);
        return $response;
    }

    public function purchaseAirtime(array $data)
    {
        // $data should contain: amount, phoneNumber, serviceCategoryId, debitAccountNumber
        $data['channel'] = 'WEB';
        return $this->post('/vas/pay/airtime', $data);
    }

    public function purchaseData(array $data)
    {
        // $data should contain: bundleCode, amount, phoneNumber, serviceCategoryId, debitAccountNumber
        $data['channel'] = 'WEB';
        return $this->post('/vas/pay/data', $data);
    }


    // ======================================================
    // VIRTUAL ACCOUNT METHODS
    // ======================================================


    public function createVirtualAccount($data){
         return  $response = $this->post("/virtual-accounts", $data);
    }

}
