<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use PolygonIO\Rest\Rest;

class PolygonClient
{
    private Rest $rest;

    public function __construct()
    {
        $this->rest = new Rest(Config::get('market-data-service.polygon_io.key'));
    }

    public function getStockAggregates(String $tickerSymbol, int $multiplier, String $timespan, Carbon $from, Carbon $to)
    {
        return $this
            ->rest
            ->stocks()
            ->aggregates()
            ->get(
                $tickerSymbol,
                multiplier:$multiplier,
                timespan:$timespan,
                from:$from->toDateString(),
                to:$to->toDateString()
            );
    }
}
