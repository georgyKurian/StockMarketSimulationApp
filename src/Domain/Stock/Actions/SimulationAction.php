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

    public function execute(Ticker $ticker, int $threshold)
    {
        throw_if($ticker->candleSticks()->doesntExist(), 'Stock dosen\'t have any data to simulate');

        DB::transaction(function () use ($ticker, $threshold) {
            $simulation = Simulation::create([
                'ticker_id' => $ticker->id,
                'threshold' => $threshold,
            ]);

            $simulation
                ->candleSticks()
                ->select('day')
                ->distinct()
                ->orderBy('day')
                ->each(function (CandleStick $candleStick) use ($simulation) {
                    $candleSticks = $simulation
                        ->candleSticks()
                        ->where('day', $candleStick->day)
                        ->where('time', '>=', 1000)
                        ->whereNotNull('volume')
                        ->orderByDateTimeOldest()
                        ->get();

                    $this->daySimulation->execute($candleStick->day, $candleSticks, $simulation);
                });

            $this->generateSimulationResult($simulation);
        });
    }

    private function generateSimulationResult(Simulation $simulation)
    {
        $aggregateResult = $simulation
            ->days()
            ->selectRaw('SUM(long_profit) as long_profit, SUM(short_profit) as short_profit, avg(long_enter_at_price) as average_long_enter_at_price, sum(profit_percentage) as profit_percentage')
            ->first();

        $longEnteredDays = $simulation
            ->days()
            ->where('long_profit', '!=', 0)
            ->count();

        $shortEnteredDays = $simulation
            ->days()
            ->where('short_profit', '!=', 0)
            ->count();

        $longNetProfit = $aggregateResult->long_profit - ($longEnteredDays * 2);
        $shortNetProfit = $aggregateResult->short_profit - ($shortEnteredDays * 2);
        
        $totalNetProfit = $longNetProfit + $shortNetProfit;
        $netProfitPercentage = round($totalNetProfit / $aggregateResult->average_long_enter_at_price);

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
            'net_profit_percentage' => $netProfitPercentage,
        ]);
    }
}
