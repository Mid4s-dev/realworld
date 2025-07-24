<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /**
     * Return a JSON response.
     */
    protected function respond($data, int $statusCode = 200, array $headers = []): JsonResponse
    {
        return response()->json($data, $statusCode, $headers);
    }

    /**
     * Return a success response.
     */
    protected function respondSuccess($message = 'Success'): JsonResponse
    {
        return $this->respond(['message' => $message]);
    }

    /**
     * Return an error response.
     */
    protected function respondError($message, int $statusCode = 400): JsonResponse
    {
        return $this->respond(['error' => $message], $statusCode);
    }

    /**
     * Return a not found response.
     */
    protected function respondNotFound($message = 'Not Found'): JsonResponse
    {
        return $this->respondError($message, 404);
    }

    /**
     * Return an unauthorized response.
     */
    protected function respondUnauthorized($message = 'Unauthorized'): JsonResponse
    {
        return $this->respondError($message, 401);
    }

    /**
     * Return a forbidden response.
     */
    protected function respondForbidden($message = 'Forbidden'): JsonResponse
    {
        return $this->respondError($message, 403);
    }

    /**
     * Return a validation error response.
     */
    protected function respondValidationError($errors): JsonResponse
    {
        return $this->respond(['errors' => $errors], 422);
    }
}
