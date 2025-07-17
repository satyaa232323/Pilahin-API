<?php

namespace App\Helper;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    /**
     * Return a standardized JSON response
     *
     * @param bool $success
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function jsonResponse(bool $success, string $message, $data = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Return a success response
     *
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function success(string $message = 'Success', $data = null, int $statusCode = 200): JsonResponse
    {
        return self::jsonResponse(true, $message, $data, $statusCode);
    }

    /**
     * Return an error response
     *
     * @param string $message
     * @param mixed $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function error(string $message = 'Error', $data = null, int $statusCode = 400): JsonResponse
    {
        return self::jsonResponse(false, $message, $data, $statusCode);
    }

    /**
     * Return a validation error response
     *
     * @param string $message
     * @param mixed $errors
     * @return JsonResponse
     */
    public static function validationError(string $message = 'Validation failed', $errors = null): JsonResponse
    {
        return self::jsonResponse(false, $message, $errors, 422);
    }

    /**
     * Return an unauthorized response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::jsonResponse(false, $message, null, 401);
    }

    /**
     * Return a not found response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function notFound(string $message = 'Not found'): JsonResponse
    {
        return self::jsonResponse(false, $message, null, 404);
    }

    /**
     * Return a server error response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function serverError(string $message = 'Internal server error'): JsonResponse
    {
        return self::jsonResponse(false, $message, null, 500);
    }

    /**
     * Return a forbidden response
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return self::jsonResponse(false, $message, null, 403);
    }
}
