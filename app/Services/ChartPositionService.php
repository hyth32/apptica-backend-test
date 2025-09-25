<?php

namespace App\Services;

use Exception;
use App\Services\Api\ChartPositionResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class ChartPositionService
{
    private string $baseUrl;
    private int $applicationId;
    private int $countryId;

    public function __construct() {
        $this->baseUrl = config('services.chart.base_url');
        $this->applicationId = config('services.chart.application_id');
        $this->countryId = config('services.chart.country_id');
    }

    public function buildUrl(string $date): string
    {
        $params = [
            'date_from' => $date,
            'date_to' => $date,
            'B4NKGg' => 'fVN5Q9KVOlOHDx9mOsKPAQsFBlEhBOwguLkNEDTZvKzJzT3l',
        ];

        $apiUrl = "$this->baseUrl/$this->applicationId/$this->countryId";

        return URL::query($apiUrl, $params);
    }

    public function getPositions(string $date): ChartPositionResponse
    {
        try {
            $apiUrl = $this->buildUrl($date);
            $response = Http::retry(3, 100)->get($apiUrl);
            if (!$response->successful()) {
                throw new Exception('API request failed');
            }

            return ChartPositionResponse::fromArray($response->json());
        } catch (Exception $e) {
            return ChartPositionResponse::fromArray([
                'status_code' => $e->getCode(),
                'message' => $e->getMessage(),
                'data' => [],
            ]);
        }
    }
}
