<?php

namespace App\Services\Api;

use Illuminate\Support\Collection;

class ChartPositionResponse extends ApiResponse
{
    protected static function parseData(array $data): Collection
    {
        return collect($data)->map(function ($categories, $categoryId) {
            return collect($categories)->map(function ($subcategories) use ($categoryId) {
                return collect($subcategories)->map(function ($value, $date) use ($categoryId) {
                    return [
                        'category_id' => $categoryId,
                        'date' => $date,
                        'value' => $value,
                    ];
                });
            });
        });
    }
}
