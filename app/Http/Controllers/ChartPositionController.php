<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChartPositionRequest;
use App\Services\ChartPositionService;

class ChartPositionController extends Controller
{
    public function __construct(
        private ChartPositionService $service,
    ) {}

    public function index(ChartPositionRequest $request)
    {
        $date = $request->validated('date');
        $response = $this->service->getPositions($date);

        if (!$response->isSuccessful()) {
            return response()->json([
                'success' => false,
                'error' => $response->message,
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
