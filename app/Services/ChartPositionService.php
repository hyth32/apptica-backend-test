<?php

namespace App\Services;

use Exception;
use App\Services\Api\ChartPositionResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;

class ChartPositionService
{
    private const MAX_RETRIES = 3;
    private const RETRY_DELAY = 100;

    private string $apiToken;
    private string $baseUrl;
    private int $applicationId;
    private int $countryId;

    public function __construct() {
        $this->apiToken = config('services.chart.api_token');
        $this->baseUrl = config('services.chart.base_url');
        $this->applicationId = config('services.chart.application_id');
        $this->countryId = config('services.chart.country_id');
    }

    public function buildUrl(string $date): string
    {
        $params = [
            'date_from' => $date,
            'date_to' => $date,
            'B4NKGg' => $this->apiToken,
        ];

        $apiUrl = "{$this->baseUrl}/{$this->applicationId}/{$this->countryId}";

        return URL::query($apiUrl, $params);
    }

    public function getPositions(string $date): ChartPositionResponse
    {
        try {
            $apiUrl = $this->buildUrl($date);
            $response = $this->makeHttpRequest($apiUrl);
            
            if (!$response->successful()) {
                $this->logApiError($response, $date);
                throw new Exception('API request failed with status: ' . $response->status());
            }

            return ChartPositionResponse::fromArray($response->json());
        } catch (Exception $e) {
            Log::error('ChartPositionService error', [
                'date' => $date,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return $this->createErrorResponse($e);
        }
    }

    private function makeHttpRequest(string $url)
    {
        return Http::retry(self::MAX_RETRIES, self::RETRY_DELAY)
            ->timeout(30)
            ->get($url);
    }

    private function logApiError($response, string $date): void
    {
        Log::error('Chart API request failed', [
            'date' => $date,
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
    }

    private function createErrorResponse(Exception $e): ChartPositionResponse
    {
        return ChartPositionResponse::fromArray([
            'status_code' => $e->getCode() ?: 500,
            'message' => $e->getMessage(),
            'data' => [],
        ]);
    }
}
