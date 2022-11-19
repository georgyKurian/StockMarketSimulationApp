<?php

namespace Services\MarketDataService;

use App\Models\Ticker;
use Illuminate\Support\Carbon;
use Services\MarketDataService\DataTransferObjects\StockAggregatesData;

abstract class Client
{
    abstract public function getStockAggregates(Ticker $ticker, int $multiplier, String $timespan, Carbon $from, Carbon $to): StockAggregatesData;
}
