<?php

namespace App\Models;

use Domain\Stock\QueryBuilders\TickerQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

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

    public function simulations()
    {
        return $this->hasMany(Simulation::class);
    }

    public function bestPerformingSimulation()
    {
        return $this
            ->hasOne(Simulation::class)
            ->orderByDesc('profit_percentage');
    }

    public function getCandleDateRange(): String
    {
        /** type @var Carbon */
        $from = $this->candleSticks()->oldest('recorded_at')?->first()->recorded_at;
        /** type @var Carbon */
        $to = $this->candleSticks()->latest('recorded_at')?->first()->recorded_at;

        if (! $from || ! $to) {
            return '';
        }

        return $from->format('M d, Y').' - '.$to->format('M d, Y');
    }
}
