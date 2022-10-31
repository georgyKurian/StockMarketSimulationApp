<?php

namespace App\Models;

use Domain\Strategy1Analysics\Collections\IntradayCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Intraday extends Model
{
    use HasFactory;

    protected $casts = [
        'open' => 'float',
        'high' => 'float',
        'low' => 'float',
        'close' => 'float',
        'volume' => 'float',
        'vw_avg_price' => 'float',
        'recorded_at' => 'float',
    ];

    public function newCollection(array $models = []): IntradayCollection
    {
        return new IntradayCollection($models);
    }
}
