<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use PolygonIO\Rest\Rest;

class PolygonClient
{
    private Rest $rest;

    public function __construct()
    {
        $this->rest = new Rest('yrLUPFT4IFuA3bsJwjANA8poBaeqhLzJ');
    }

    public function getStockAggregates(String $tickerSymbol, int $multiplier, String $timespan, Carbon $from, Carbon $to)
    {
        return $this->rest->stocks()->aggregates()->get($tickerSymbol, multiplier:$multiplier, timespan:$timespan, from:$from->toDateString(), to:$to->toDateString());
    }
}
