<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChartPositionRequest;
use App\Models\ChartPosition;
use App\Services\ChartPositionService;
use Illuminate\Http\JsonResponse;

class ChartPositionController extends Controller
{
    private const HTTP_OK = 200;
    private const HTTP_PAYMENT_REQUIRED = 402;
    private const HTTP_TOO_MANY_REQUESTS = 429;

    public function __construct(
        private ChartPositionService $service,
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/chart-positions",
     *     summary="Получение информации позиции приложения в топе по конкретной дате",
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Дата в формате Y-m-d",
     *         required=true,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="chart_positions",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="category_id", type="string", description="ID категории"),
     *                         @OA\Property(property="date", type="string", description="Дата"),
     *                         @OA\Property(property="value", type="integer", description="Значение")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=402,
     *         description="Ошибка при получении данных",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 description="Сообщение об ошибке"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 example="[]",
     *                 @OA\Items(type="string"),
     *                 description="Пустой массив при ошибке"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Слишком много запросов",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="error", type="string", example="Too Many Requests"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 example="[]",
     *                 @OA\Items(type="string"),
     *                 description="Пустой массив при ошибке"
     *             )
     *         )
     *     )
     * )
     */
    public function index(ChartPositionRequest $request): JsonResponse
    {
        $date = $request->validated('date');
        
        $chartPositions = $this->getChartPositions($date);
        
        if ($chartPositions === null) {
            return $this->errorResponse('Ошибка при получении данных', self::HTTP_PAYMENT_REQUIRED);
        }

        return $this->successResponse($chartPositions);
    }

    private function getChartPositions(string $date): ?array
    {
        $existingPositions = $this->getExistingPositions($date);
        
        if ($existingPositions !== null) {
            return $existingPositions;
        }

        return $this->fetchAndStorePositions($date);
    }

    private function getExistingPositions(string $date): ?array
    {
        $positions = ChartPosition::where('date', $date)->get();
        
        return $positions->isNotEmpty() ? $positions->toArray() : null;
    }

    private function fetchAndStorePositions(string $date): ?array
    {
        $response = $this->service->getPositions($date);

        if (!$response->isSuccessful()) {
            return null;
        }

        return $response->data?->toArray() ?? [];
    }

    private function successResponse(array $data): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'chart_positions' => $data,
            ],
        ], self::HTTP_OK);
    }

    private function errorResponse(string $message, int $statusCode): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => $message,
            'data' => [],
        ], $statusCode);
    }
}
