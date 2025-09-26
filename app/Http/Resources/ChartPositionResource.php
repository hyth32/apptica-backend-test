<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChartPositionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'category_id' => $this->category_id,
            'value' => $this->value,
        ];
    }

    public static function collection($resource)
    {
        return parent::collection($resource)->additional([
            'success' => true,
        ]);
    }

    public static function success(array $data): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public static function error(string $message, int $statusCode = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => $message,
            'data' => [],
        ], $statusCode);
    }
}
