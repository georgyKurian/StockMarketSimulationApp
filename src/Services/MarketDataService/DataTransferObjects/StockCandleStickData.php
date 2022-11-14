<?php

namespace Services\MarketDataService\DataTransferObjects;

use Illuminate\Support\Carbon;

class StockCandleStickData
{
    public Carbon $startTime;
    public function __construct(
        int $startTimestamp,
        public 	float $open,
        public 	float $high,
        public 	float $low,
        public 	float $close,
        public 	int $volume,
        public 	int $numberOfTransactions,
        public 	float $volumeWeightedAveragePrice,
    ) {
        $this->startTime = Carbon::createFromTimestampMs($startTimestamp);
    }
}