<?php

namespace Domain\Stock\Actions;

use App\Models\Day;
use App\Models\Simulation;
use Domain\Stock\Collections\CandleStickCollection;
use Domain\Stock\DataTransferObjects\DaySimulationResultData;
use Domain\Strategy\Calculators\SimpleDaySimulationCalculator;
use Illuminate\Support\Carbon;

class DaySimulation
{
    public function execute(Carbon $day, CandleStickCollection $candleStickCollection, Simulation $simulation)
    {
        if ($candleStickCollection->count() <= 2) {
            return;
        }

        $resultsData = (new SimpleDaySimulationCalculator($day, $candleStickCollection, $simulation))->calculate();

        if ($resultsData) {
            $this->saveReportToDatabase($simulation, $day, $resultsData);
        }
    }

    private function saveReportToDatabase(Simulation $simulation, Carbon $day, DaySimulationResultData $daySimulationResultData)
    {
        Day::create([
            'day' => $day,
            'ticker_id' => $simulation->ticker_id,
            'simulation_id' => $simulation->id,
            'long_start_at_candle_stick_id' => $daySimulationResultData->longEnterAtCandleStick?->id,
            'long_end_at_candle_stick_id' => $daySimulationResultData->longExitAtCandleStick?->id,
            'long_enter_at_price' => $daySimulationResultData->longEnterAtPrice,
            'long_exit_at_price' => $daySimulationResultData->longExitAtPrice,
            'long_profit' => $daySimulationResultData->longProfit,

            'short_start_at_candle_stick_id' => $daySimulationResultData->shortEnterAtCandleStick?->id,
            'short_end_at_candle_stick_id' => $daySimulationResultData->shortExitAtCandleStick?->id,
            'short_enter_at_price' => $daySimulationResultData->shortEnterAtPrice,
            'short_exit_at_price' => $daySimulationResultData->shortExitAtPrice,
            'short_profit' => $daySimulationResultData->shortProfit,

            'total_profit' => $daySimulationResultData->totalProfit,
            'profit_percentage' => $daySimulationResultData->profitPercentage,
        ]);
    }
}
