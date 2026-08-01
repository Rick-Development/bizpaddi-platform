<?php

namespace Modules\ClientPortal\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /**
     * Return a success JSON response.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  int  $code
     * @return JsonResponse
     */
    public function sendResponse($data, $message = 'Success', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  array   $errorMessages
     * @param  int  $code
     * @return JsonResponse
     */
    public function sendError($message, $code = 404, $errorMessages = [])
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
