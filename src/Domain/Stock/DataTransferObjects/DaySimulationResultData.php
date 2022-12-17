<?php

namespace Domain\Stock\DataTransferObjects;

use App\Models\CandleStick;

class DaySimulationResultData
{
    public function __construct(
        public ?CandleStick $longEnterAtCandleStick,
        public ?Candlestick $longExitAtCandleStick,
        public ?int $longEnterAtPrice,
        public ?int $longExitAtPrice,
        public int $longProfit,
        public ?Candlestick $shortEnterAtCandleStick,
        public ?Candlestick $shortExitAtCandleStick,
        public ?int $shortEnterAtPrice,
        public ?int $shortExitAtPrice,
        public int $shortProfit,
        public int $totalProfit,
        public int $profitPercentage,
    ) {
    }
}
