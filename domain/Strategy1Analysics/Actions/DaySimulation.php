<?php

namespace Domain\Strategy1Analysics\Actions;

use App\Models\Day;
use App\Models\CandleStick;
use Domain\Strategy1Analysics\Collections\CandleStickCollection;

class DaySimulation
{
    private static int $timeIndexForAnalysis = 1000;
    private static int $exitTime = 1545;

    private ?CandleStick $longEnterAtCandleStick;
    private ?CandleStick $longExitAtCandleStick;
    private ?float $longEnterAtPrice;
    private ?float $longExitAtPrice;
    private ?float $longProfit;
    private int $longPositionStage;


    private ?CandleStick $shortEnterAtCandleStick;
    private ?CandleStick $shortExitAtCandleStick;
    private ?float $shortEnterAtPrice;
    private ?float $shortExitAtPrice;
    private ?float $shortProfit;
    private int $shortPositionStage;

    private ?float $totalProfit;

    public function __construct(private int $dayIndex, private CandleStickCollection $candleStickCollection)
    {
    }

    public function execute(float $bufferSize)
    {
        $this
            ->initialize()
            ->computeEntryAndExitCriteria($bufferSize)
            ?->simulateRestOfTheDay()
            ?->calculateProfitOrLoss()
            //?->dumpReport();
            ?->saveReportToDatabase();
    }

    private function initialize()
    {
        $this->longEnterAtCandleStick = null;
        $this->longExitAtCandleStick = null;
        $this->longEnterAtPrice = null;
        $this->longExitAtPrice = null;

        $this->shortEnterAtCandleStick = null;
        $this->shortExitAtCandleStick = null;
        $this->shortEnterAtPrice = null;
        $this->shortExitAtPrice = null;

        $this->longPositionStage = 1;
        $this->shortPositionStage = 1;

        return $this;
    }

    private function computeEntryAndExitCriteria(float $bufferSize)
    {
        $candleStickForAnalyzing = $this->candleStickCollection->findCandleStickByTime(self::$timeIndexForAnalysis);

        if (!$candleStickForAnalyzing) {
            return null;
        }

        $this->longEnterAtCandleStick = $candleStickForAnalyzing;
        $this->shortEnterAtCandleStick = $candleStickForAnalyzing;

        $this->longEnterAtPrice = $candleStickForAnalyzing->high + $bufferSize;
        $this->shortEnterAtPrice = $candleStickForAnalyzing->low - $bufferSize;

        return $this;
    }

    private function simulateRestOfTheDay(): self
    {
        $restOfTheDaysCandlesticks = $this
            ->candleStickCollection
            ->filterCandleSticksBetweenTime(self::$timeIndexForAnalysis, self::$exitTime)
            ->orderByTime();

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

    public function calculateProfitOrLoss()
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

        return $this;
    }


    private function dumpReport()
    {
        dump([
            // 'index' => $this->dayIndex,
            // 'Long Enter at Price' => $this->longEnterAtPrice,
            // 'Long Exit at Price' => $this->longExitAtPrice,
            // 'Profit Long' => $this->longProfit,
            // 'Short Enter at Price' => $this->shortEnterAtPrice,
            // 'Short Exit at Price' => $this->shortExitAtPrice,
            // 'profit Short' => $this->shortProfit,

            'Total Profit' => $this->totalProfit,
        ]);
    }

    private function saveReportToDatabase()
    {
        Day::create([
            'day_index' => $this->dayIndex,

            'long_start_at_candle_stick_id' => $this->longEnterAtCandleStick?->id,
            'long_end_at_candle_stick_id' => $this->longExitAtCandleStick?->id,
            'long_enter_at_price' => $this->longEnterAtPrice,
            'long_exit_at_price' => $this->longExitAtPrice,
            'long_profit' => $this->longProfit,

            'short_start_at_candle_stick_id' => $this->shortEnterAtCandleStick?->id,
            'short_end_at_candle_stick_id' => $this->shortExitAtCandleStick?->id,
            'short_enter_at_price' => $this->shortEnterAtPrice,
            'short_exit_at_price' => $this->shortExitAtPrice,
            'short_profit' => $this->shortProfit,

            'total_profit' => $this->totalProfit,
        ]);
    }
}
