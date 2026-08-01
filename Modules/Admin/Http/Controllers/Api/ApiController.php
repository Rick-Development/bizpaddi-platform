<?php

namespace Modules\Admin\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /**
     * Return a success JSON response.
     */
    public function sendResponse($data, $message = 'Success', $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    /**
     * Return an error JSON response.
     */
    public function sendError($message, $code = 404, $errorMessages = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errorMessages)) {
            $response['errors'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
