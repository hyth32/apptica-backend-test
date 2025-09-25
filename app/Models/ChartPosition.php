<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChartPosition extends Model
{
    protected $fillable = [
        'category_id',
        'date',
        'value',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
