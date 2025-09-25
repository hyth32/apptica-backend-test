<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChartPositionRequest;
use App\Services\ChartPositionService;

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
    public function index(ChartPositionRequest $request)
    {
        $date = $request->validated('date');
        $response = $this->service->getPositions($date);

        if (!$response->isSuccessful()) {
            return response()->json([
                'success' => false,
                'error' => $response->message,
                'data' => [],
            ], $response->statusCode);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'chart_positions' => $response->data,
            ],
        ]);
    }
}
