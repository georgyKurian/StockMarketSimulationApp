<?php

namespace Domain\Stock\DataTransferObjects;

use App\Models\CandleStick;

class DaySimulationResultData
{
    public function __construct(
        public ?CandleStick $longEnterAtCandleStick,
        public ?Candlestick $longExitAtCandleStick,
        public ?float $longEnterAtPrice,
        public ?float $longExitAtPrice,
        public float $longProfit,
        public ?Candlestick $shortEnterAtCandleStick,
        public ?Candlestick $shortExitAtCandleStick,
        public ?float $shortEnterAtPrice,
        public ?float $shortExitAtPrice,
        public float $shortProfit,
        public float $totalProfit,
    ) {
    }
}
