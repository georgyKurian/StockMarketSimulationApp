<?php

namespace App\Models;

use Domain\Stock\QueryBuilders\TickerQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticker extends Model
{
    use HasFactory;

    public function newEloquentBuilder($query): TickerQueryBuilder
    {
        return new TickerQueryBuilder($query);
    }

    public function candleSticks()
    {
        return $this->hasMany(CandleStick::class);
    }
}
