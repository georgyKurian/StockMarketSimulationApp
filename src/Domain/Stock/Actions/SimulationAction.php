<?php

namespace Domain\Stock\Actions;

use App\Models\CandleStick;
use App\Models\Simulation;
use App\Models\Ticker;
use Illuminate\Support\Facades\DB;

class SimulationAction
{
    public function __construct(private DaySimulation $daySimulation)
    {
    }

    public function execute(Ticker $ticker, float $threshold)
    {
        throw_if($ticker->candleSticks()->doesntExist(), 'Stock dosen\'t have any data to simulate');

        DB::transaction(function () use ($ticker, $threshold) {
            $simulation = Simulation::create([
                'ticker_id' => $ticker->id,
                'threshold' => $threshold,
            ]);

            $simulation
                ->candleSticks()
                ->select('day_index')
                ->distinct()
                ->orderBy('day_index')
                ->each(function (CandleStick $candleStick) use ($simulation) {
                    $candleSticks = $simulation
                        ->candleSticks()
                        ->where('day_index', $candleStick->day_index)
                        ->where('time', '>=', 1000)
                        ->whereNotNull('volume')
                        ->orderByTime()
                        ->get();

                    $this->daySimulation->execute($candleStick->day_index, $candleSticks, $simulation);
                });

            $this->generateSimulationResult($simulation);
        });
    }

    private function generateSimulationResult(Simulation $simulation)
    {
        $aggregateResult = $simulation
            ->days()
            ->selectRaw('SUM(long_profit) as long_profit, SUM(short_profit) as short_profit, SUM(profit_percentage) as profit_percentage')
            ->first();

        $longEnteredDays = $simulation
            ->days()
            ->where('long_profit', '!=', 0.0)
            ->count();

        $shortEnteredDays = $simulation
            ->days()
            ->where('short_profit', '!=', 0.0)
            ->count();

        $longNetProfit = $aggregateResult->long_profit - ($longEnteredDays * 2 * 0.01);
        $shortNetProfit = $aggregateResult->short_profit - ($shortEnteredDays * 2 * 0.01);

        $simulation->update([
            'long_profit' => $aggregateResult->long_profit,
            'short_profit' => $aggregateResult->short_profit,
            'total_profit' => ($aggregateResult->long_profit + $aggregateResult->short_profit),
            'profit_percentage' => ($aggregateResult->profit_percentage),

            'long_entered_days' => $longEnteredDays,
            'short_entered_days' => $shortEnteredDays,

            'long_net_profit' => $longNetProfit,
            'short_net_profit' => $shortNetProfit,
            'total_net_profit' =>  $longNetProfit + $shortNetProfit,
        ]);
    }
}
