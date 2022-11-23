<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Simulation;
use Illuminate\Support\Carbon;

class SimulationController extends Controller
{
    public function show(Simulation $simulation)
    {
        $days = $simulation
            ->days()
            ->orderBy('day_index')
            ->paginate(50);

        return view(
            'simulation.show',
            [
                'days' => $days
                    ->transform(function (Day $day) {
                        return [
                            'id' => $day->id,
                            'date' => Carbon::createFromIsoFormat('YMMDD', $day->day_index)->toDateString(),
                            'long_profit' => number_format($day->long_profit, 2),
                            'short_profit' => number_format($day->short_profit, 2),
                            'total_profit' => number_format($day->total_profit, 2),
                        ];
                    }),
                'simulation' => [
                    'id' => $simulation->id,
                    'ticker' => $simulation->ticker,
                    'threshold' => $simulation->threshold,
                    'long_profit' => number_format($simulation->long_profit, 2),
                    'short_profit' => number_format($simulation->short_profit, 2),
                    'total_profit' => number_format($simulation->total_profit, 2),
                    'date_range' => $simulation->dateRange(),
                ],
                'links' =>  $days->links(),
            ]
        );
    }
}
