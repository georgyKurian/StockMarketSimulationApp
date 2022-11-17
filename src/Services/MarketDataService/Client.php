<?php

namespace Services\MarketDataService;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

abstract class Client
{
    /**
     * @return Collection<StockCandleStickData>
     */
    abstract public function getStockAggregates(String $tickerSymbol, int $multiplier, String $timespan, Carbon $from, Carbon $to);
}
