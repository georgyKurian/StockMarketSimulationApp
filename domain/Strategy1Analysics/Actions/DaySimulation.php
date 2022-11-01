<?php

namespace Domain\Strategy1Analysics\Actions;

use Domain\Strategy1Analysics\Collections\IntradayCollection;

class DaySimulation
{
    private static int $timeIndexForAnalysis = 1000;
    private static int $exitTime = 1545;

    private ?float $longEnterAtPrice;
    private ?float $longExitAtPrice;
    private ?float $longProfit;
    private int $longPositionStage;


    private ?float $shortEnterAtPrice;
    private ?float $shortExitAtPrice;
    private ?float $shortProfit;
    private int $shortPositionStage;

    private ?float $totalProfit;

    public function __construct(private int $dayIndex, private IntradayCollection $intradayCollection)
    {
    }

    public function execute(float $bufferSize)
    {
        $this
            ->initialize()
            ->computeEntryAndExitCriteria($bufferSize)
            ?->simulateRestOfTheDay()
            ?->calculateProfitOrLoss()
            ?->dumpReport();
    }

    private function initialize()
    {
        $this->longEnterAtPrice = null;
        $this->longExitAtPrice = null;

        $this->shortEnterAtPrice = null;
        $this->shortExitAtPrice = null;

        $this->longPositionStage = 1;
        $this->shortPositionStage = 1;

        return $this;
    }

    private function computeEntryAndExitCriteria(float $bufferSize)
    {
        $intradayForAnalyzing = $this->intradayCollection->findCandleStickByTime(self::$timeIndexForAnalysis);

        if (!$intradayForAnalyzing) {
            return null;
        }

        $this->longEnterAtPrice = $intradayForAnalyzing->high + $bufferSize;
        $this->shortEnterAtPrice = $intradayForAnalyzing->low - $bufferSize;

        return $this;
    }

    private function simulateRestOfTheDay(): self
    {
        $restOfTheDaysCandlesticks = $this
            ->intradayCollection
            ->filterCandleSticksBetweenTime(self::$timeIndexForAnalysis, self::$exitTime)
            ->orderByTime();

        foreach ($this->intradayCollection as $candleStick) {
            if ($this->longPositionStage === 1 && $candleStick->high >= $this->longEnterAtPrice) {
                // buy
                $this->longPositionStage = 2;
            } elseif ($this->longPositionStage === 2 && $candleStick->low <= $this->shortEnterAtPrice) {
                $this->longPositionStage = 3;
                $this->longExitAtPrice = $this->shortEnterAtPrice;
            }

            if ($this->shortPositionStage === 1 && $candleStick->low <= $this->shortEnterAtPrice) {
                $this->shortPositionStage = 2;
            } elseif ($this->shortPositionStage === 2 && $candleStick->high >= $this->longEnterAtPrice) {
                $this->shortPositionStage = 3;
                $this->shortExitAtPrice = $this->longEnterAtPrice;
            }
        }

        $exitCandle = $this
            ->intradayCollection
            ->findCandleStickByTime(self::$exitTime);

        if ($exitCandle) {
            if ($this->longPositionStage === 2) {
                $this->longPositionStage = 3;
                $this->longExitAtPrice = $exitCandle->vw_avg_price;
            }

            if ($this->shortPositionStage === 2) {
                $this->shortPositionStage = 3;
                $this->shortExitAtPrice = $exitCandle->vw_avg_price;
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
}
