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
        });
    }
}
