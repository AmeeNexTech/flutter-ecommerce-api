<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponse
{
    /**
     * استجابة نجاح مع بيانات
     */
    protected function successResponse($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ], $code);
    }

    /**
     * استجابة نجاح بدون بيانات
     */
    protected function successMessage(string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ], $code);
    }

    /**
     * استجابة خطأ
     */
    protected function errorResponse(string $message = 'Error', int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'status' => 'error',
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * استجابة مع Resource
     */
    protected function resourceResponse(JsonResource $resource, string $message = 'Success', int $code = 200): JsonResponse
    {
        return $resource->additional([
            'status' => 'success',
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ])->response()->setStatusCode($code);
    }

    /**
     * استجابة مع Resource Collection
     */
    protected function collectionResponse(ResourceCollection $collection, string $message = 'Success', int $code = 200): JsonResponse
    {
        return $collection->additional([
            'status' => 'success',
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ])->response()->setStatusCode($code);
    }

    /**
     * استجابة للتحقق من الصحة
     */
    protected function validationErrorResponse($errors, string $message = 'Validation failed'): JsonResponse
    {
        return $this->errorResponse($message, 422, $errors);
    }

    /**
     * استجابة للموارد غير الموجودة
     */
    protected function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }

    /**
     * استجابة للوصول المرفوض
     */
    protected function forbiddenResponse(string $message = 'Access denied'): JsonResponse
    {
        return $this->errorResponse($message, 403);
    }

    /**
     * استجابة للخطأ الداخلي
     */
    protected function serverErrorResponse(string $message = 'Internal server error'): JsonResponse
    {
        return $this->errorResponse($message, 500);
    }
}
