<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class ChartPositionService
{
    private string $apiUrl;

    public function __construct() {
        $this->apiUrl = config('services.chart.api_url');
    }

    public function getPositions(array $params = [])
    {
        try {
            $response = Http::retry(3, 100)->get($this->apiUrl, $params);
            if (!$response->successful()) {
                throw new Exception('API request failed');
            }

            return $response;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
