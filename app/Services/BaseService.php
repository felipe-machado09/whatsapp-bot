<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class BaseService extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message, $responseCode): JsonResponse
    {
        $response = [
            'meta' => [
                'version' => config('app.version'),
                'server' => gethostname(),
                'time' => Carbon::now(),
            ],
        ];

        if ($message) {
            $response['message'] = $message;
        }

        if ($result) {
            $response['data'] = $result;
        }

        return response()->json($response, $responseCode);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($errorData = [], $errorMessages = "", $responseCode): JsonResponse
    {
        $response = [
            'meta' => [
                'version' => config('app.version'),
                'server' => gethostname(),
                'time' => Carbon::now(),
            ],
            'message' => $errorMessages,
        ];

        if ($errorData) {
            $response['error'] = $errorData;
        }

        return response()->json($response, $responseCode);
    }
}
