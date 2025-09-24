<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChartPositionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => 'required|date',
        ];
    }

    public function attributes()
    {
        return [
            'date' => 'Дата выборки',
        ];
    }
}
