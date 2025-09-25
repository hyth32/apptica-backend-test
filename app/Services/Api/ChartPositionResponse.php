<?php

namespace App\Services\Api;

use Illuminate\Support\Collection;

class ChartPositionResponse extends ApiResponse
{
    protected static function parseData(array $data): Collection
    {
        return collect($data)->map(function ($categories) {
            $categoriesData = collect($categories)->map(function ($subcategories) {
                return collect($subcategories)->flatMap(function ($value, $date) {
                    return [
                        'date' => $date,
                        'value' => $value,
                    ];
                });
            });

            $minValue = $categoriesData->min('value');
            return [
                'date' => $categoriesData->pluck('date')->first(),
                'value' => $minValue,
            ];
        });
    }
}
