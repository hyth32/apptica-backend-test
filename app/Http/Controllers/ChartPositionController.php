<?php

namespace App\Http\Controllers;

use App\Services\ChartPositionService;

class ChartPositionController extends Controller
{
    public function __construct(
        private ChartPositionService $service,
    ) {}

    public function index()
    {
        $response = $this->service->getPositions();
        return response()->json([
            'chart_positions' => $response->body(),
        ]);
    }
}
