<?php

namespace Services\MarketDataService\DataTransferObjects;

use App\Models\Ticker;
use Illuminate\Support\Collection;

class StockAggregatesData
{
    /**
     * @param Collection<StockCandleStickData> $StockCandleStickData
     */
    public function __construct(
        public Ticker $ticker,
        public Collection $stockCandleStickDataCollection,
    ) {
    }
}
