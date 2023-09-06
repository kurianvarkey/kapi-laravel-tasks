<?php

namespace Kapi\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

trait AppResponseTrait
{
    /**
     * getDefaultHeaders
     *
     * @return void
     */
    public function getDefaultHeaders()
    {
        return [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'X-Application-Name' => env('APP_NAME', 'Kapi - Laravel'),
            'X-Application-Version' => env('APP_VERSION', '1.0.0')
        ];
    }

    /**
     * sendResponse
     *
     * @param [type] $data
     * @param [type] $http_status
     */
    public function sendResponse($data, int $httpStatus = Response::HTTP_OK, array $errors = []): JsonResponse
    {
        $wrap = '';
        if ($data instanceof JsonResource) {
            if (isset($data::$wrap) && $data::$wrap != 'data') {
                $wrap = $data::$wrap;
            }
        }

        $data = array_merge([
            'status' => ($httpStatus == 200 || $httpStatus == 201 ? 1 : 0),
            'code' => $httpStatus,
            'errors' => $errors,
        ], ['data' => ($wrap != '' ? [$wrap => $data] : $data)]);

        return response()
            ->json($data, $httpStatus)
            ->withHeaders($this->getDefaultHeaders());
    }

    /**
     * sendThrottleResponse
     */
    public function sendErrorResponse(int $httpStatus = Response::HTTP_BAD_REQUEST, array $errors = []): JsonResponse
    {
        return response()->json([
            'status' => 0,
            'code' => $httpStatus,
            'error' => implode('. ', $errors)
        ], $httpStatus)
        ->withHeaders($this->getDefaultHeaders());
    }

    /**
     * sendThrottleResponse
     */
    public function sendThrottleResponse(string $error, array $headers = []): JsonResponse
    {
        return response()->json([
            'status' => 0,
            'code' => Response::HTTP_TOO_MANY_REQUESTS,
            'error' => $error,
            'retry-after' => (isset($headers['Retry-After']) ? $headers['Retry-After'] : 60) . ' seconds',
        ], Response::HTTP_TOO_MANY_REQUESTS)
        ->withHeaders(array_merge($this->getDefaultHeaders(), [
            'Retry-After' => (isset($headers['Retry-After']) ? $headers['Retry-After'] : 60),
        ]));
    }
}
