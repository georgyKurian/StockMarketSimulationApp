<?php

namespace Services\MarketDataService\DataTransferObjects;

use Illuminate\Support\Carbon;

class StockCandleStickData
{
    public Carbon $startTime;

    public function __construct(
        int $startTimestamp,
        public 	int $open,
        public 	int $high,
        public 	int $low,
        public 	int $close,
        public 	?int $volume,
        public 	?int $numberOfTransactions,
        public 	?int $volumeWeightedAveragePrice,
    ) {
        $this->startTime = Carbon::createFromTimestampMs($startTimestamp);
    }
}
