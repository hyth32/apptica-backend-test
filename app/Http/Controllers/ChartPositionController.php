<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChartPositionRequest;
use App\Http\Resources\ChartPositionResource;
use App\Services\ChartPositionService;
use Illuminate\Http\JsonResponse;

class ChartPositionController extends Controller
{
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
     *                 description="ID категории => позиция в топе",
     *                 example={
     *                     "1": 5,
     *                     "2": 12,
     *                     "3": 8
     *                 }
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
        
        $chartPositions = $this->service->getChartPositionsForDate($date);
        
        if ($chartPositions === null) {
            return ChartPositionResource::error('Ошибка при получении данных', 402);
        }

        return ChartPositionResource::success($chartPositions);
    }
}
