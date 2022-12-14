<?php

namespace Domain\Strategy\Calculators;

use App\Models\CandleStick;
use App\Models\Simulation;
use Domain\Stock\Collections\CandleStickCollection;
use Domain\Stock\DataTransferObjects\DaySimulationResultData;
use Illuminate\Support\Carbon;

class SimpleDaySimulationCalculator
{
    private static int $timeIndexForAnalysis = 1000;
    private static int $exitTime = 1545;

    private ?CandleStick $longEnterAtCandleStick;
    private ?CandleStick $longExitAtCandleStick;
    private ?float $longEnterAtPrice;
    private ?float $longExitAtPrice;
    private float $longProfit = 0;
    private int $longPositionStage;

    private ?CandleStick $shortEnterAtCandleStick;
    private ?CandleStick $shortExitAtCandleStick;
    private ?float $shortEnterAtPrice;
    private ?float $shortExitAtPrice;
    private float $shortProfit = 0.0;
    private int $shortPositionStage;

    private float $totalProfit = 0.0;

    public function __construct(private Carbon $day, private  CandleStickCollection $candleStickCollection, private  Simulation $simulation)
    {
    }

    public function calculate(): ?DaySimulationResultData
    {
        return $this
            ->initialize()
            ->computeEntryAndExitCriteria()
            ?->simulateRestOfTheDay()
            ?->calculateProfitOrLoss()
            //?->dumpReport();
            ?->getResults();
    }

    private function initialize()
    {
        $this->longEnterAtCandleStick = null;
        $this->longExitAtCandleStick = null;
        $this->longEnterAtPrice = null;
        $this->longExitAtPrice = null;
        $this->longProfit = 0.0;

        $this->shortEnterAtCandleStick = null;
        $this->shortExitAtCandleStick = null;
        $this->shortEnterAtPrice = null;
        $this->shortExitAtPrice = null;
        $this->shortProfit = 0.0;

        $this->totalProfit = 0.0;

        $this->longPositionStage = 1;
        $this->shortPositionStage = 1;

        return $this;
    }

    private function computeEntryAndExitCriteria()
    {
        $candleStickForAnalyzing = $this->candleStickCollection->findCandleStickByTime(self::$timeIndexForAnalysis);

        if (! $candleStickForAnalyzing) {
            return null;
        }

        $this->longEnterAtCandleStick = null;
        $this->shortEnterAtCandleStick = null;

        $this->longEnterAtPrice = $candleStickForAnalyzing->high + $this->simulation->threshold;
        $this->shortEnterAtPrice = $candleStickForAnalyzing->low - $this->simulation->threshold;

        return $this;
    }

    private function simulateRestOfTheDay(): self
    {
        $restOfTheDaysCandlesticks = $this
            ->candleStickCollection
            ->filterCandleSticksBetweenTime(self::$timeIndexForAnalysis, self::$exitTime)
            ->orderByDateTimeOldest();

        foreach ($restOfTheDaysCandlesticks as $candleStick) {
            if ($this->longPositionStage === 1 && $candleStick->high >= $this->longEnterAtPrice) {
                // buy
                $this->longPositionStage = 2;
                $this->longEnterAtCandleStick = $candleStick;
            } elseif ($this->longPositionStage === 2 && $candleStick->low <= $this->shortEnterAtPrice) {
                $this->longPositionStage = 3;
                $this->longExitAtCandleStick = $candleStick;
                $this->longExitAtPrice = $this->shortEnterAtPrice;
            }

            if ($this->shortPositionStage === 1 && $candleStick->low <= $this->shortEnterAtPrice) {
                $this->shortPositionStage = 2;
                $this->shortEnterAtCandleStick = $candleStick;
            } elseif ($this->shortPositionStage === 2 && $candleStick->high >= $this->longEnterAtPrice) {
                $this->shortPositionStage = 3;
                $this->shortExitAtCandleStick = $candleStick;
                $this->shortExitAtPrice = $this->longEnterAtPrice;
            }
        }

        $exitCandle = $this
            ->candleStickCollection
            ->findCandleStickByTime(self::$exitTime);

        if ($exitCandle) {
            if ($this->longPositionStage === 2) {
                $this->longPositionStage = 3;
                $this->longExitAtPrice = $exitCandle->open;
                $this->longExitAtCandleStick = $exitCandle;
            }

            if ($this->shortPositionStage === 2) {
                $this->shortPositionStage = 3;
                $this->shortExitAtPrice = $exitCandle->open;
                $this->shortExitAtCandleStick = $exitCandle;
            }
        }

        return $this;
    }

    private function calculateProfitOrLoss()
    {
        if ($this->longExitAtPrice && $this->longEnterAtPrice) {
            $this->longProfit = $this->longExitAtPrice - $this->longEnterAtPrice;
        } else {
            $this->longProfit = 0.0;
        }

        if ($this->shortExitAtPrice && $this->shortEnterAtPrice) {
            $this->shortProfit = $this->shortEnterAtPrice - $this->shortExitAtPrice;
        } else {
            $this->shortProfit = 0.0;
        }

        $this->totalProfit = $this->longProfit + $this->shortProfit;
        $this->profitPercentage = $this->totalProfit * 100 / $this->longEnterAtPrice;

        return $this;
    }

    private function dumpReport()
    {
        dump([
            'day' => $this->day->toDateString(),
            // 'Long Enter at Price' => $this->longEnterAtPrice,
            // 'Long Exit at Price' => $this->longExitAtPrice,
            // 'Profit Long' => $this->longProfit,
            // 'Short Enter at Price' => $this->shortEnterAtPrice,
            // 'Short Exit at Price' => $this->shortExitAtPrice,
            // 'profit Short' => $this->shortProfit,

            'long_start_at_candle_stick_id' => $this->longEnterAtCandleStick?->id,
            'long_end_at_candle_stick_id' => $this->longExitAtCandleStick?->id,
            // 'long_enter_at_price' => $this->longEnterAtPrice,
            // 'long_exit_at_price' => $this->longExitAtPrice,
            // 'long_profit' => $this->longProfit,

            'short_start_at_candle_stick_id' => $this->shortEnterAtCandleStick?->id,
            'short_end_at_candle_stick_id' => $this->shortExitAtCandleStick?->id,
            // 'short_enter_at_price' => $this->shortEnterAtPrice,
            // 'short_exit_at_price' => $this->shortExitAtPrice,
            // 'short_profit' => $this->shortProfit,

            'Total Profit' => $this->totalProfit,
        ]);
    }

    private function getResults(): DaySimulationResultData
    {
        return
            new DaySimulationResultData(
                longEnterAtCandleStick: $this->longEnterAtCandleStick,
                longExitAtCandleStick: $this->longExitAtCandleStick,
                longEnterAtPrice: $this->longEnterAtPrice,
                longExitAtPrice: $this->longExitAtPrice,
                longProfit: $this->longProfit,
                shortEnterAtCandleStick: $this->shortEnterAtCandleStick,
                shortExitAtCandleStick: $this->shortExitAtCandleStick,
                shortEnterAtPrice: $this->shortEnterAtPrice,
                shortExitAtPrice: $this->shortExitAtPrice,
                shortProfit: $this->shortProfit,
                totalProfit: $this->totalProfit,
                profitPercentage: $this->profitPercentage,
            );
    }
}
