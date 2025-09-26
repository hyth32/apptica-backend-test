<?php

namespace App\Services\Api;

use App\Models\ChartPosition;
use Illuminate\Support\Collection;

class ChartPositionResponse extends ApiResponse
{
    protected static function parseData(array $data): Collection
    {
        if (empty($data)) {
            return collect();
        }

        return collect($data)->map(function ($categories, $categoryId) {
            return self::processCategoryData($categoryId, $categories);
        })->values();
    }

    private static function processCategoryData(string $categoryId, array $categories): ChartPosition
    {
        $categoriesData = collect($categories)->map(function ($subcategories) {
            return collect($subcategories)->flatMap(function ($value, $date) {
                return [
                    'date' => $date,
                    'value' => $value,
                ];
            });
        });
        
        $minValue = $categoriesData->min('value');
        $date = $categoriesData->pluck('date')->first();
        
        return ChartPosition::updateOrCreate([
            'category_id' => $categoryId,
            'date' => $date,
        ], [
            'value' => $minValue,
        ]);
    }
}
