<?php

namespace Services\MarketDataService;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use PolygonIO\Rest\Rest;

abstract class Client
{
    /**
     * @return Collection<StockCandleStickData> 
     */
    abstract  public function getStockAggregates(String $tickerSymbol, int $multiplier, String $timespan, Carbon $from, Carbon $to);
}
