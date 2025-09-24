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

        return response()->json([
            'chart_positions' => $response->body(),
        ]);
    }
}
