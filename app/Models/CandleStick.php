<?php

namespace App\Models;

use Domain\Stock\Collections\CandleStickCollection;
use Domain\Stock\QueryBuilders\CandleStickQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CandleStick extends Model
{
    use HasFactory;

    protected $casts = [
        'open' => 'float',
        'high' => 'float',
        'low' => 'float',
        'close' => 'float',
        'volume' => 'float',
        'vw_avg_price' => 'float',
        'recorded_at' => 'datetime',
    ];

    public function newCollection(array $models = []): CandleStickCollection
    {
        return new CandleStickCollection($models);
    }

    public function newEloquentBuilder($query): CandleStickQueryBuilder
    {
        return new CandleStickQueryBuilder($query);
    }

    public function ticker()
    {
        return $this->belongsTo(Ticker::class);
    }
}
