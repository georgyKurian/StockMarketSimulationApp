<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Simulation;
use Illuminate\Support\Carbon;

class SimulationController extends Controller
{
    public function index()
    {
        $simulations = Simulation::query()
            ->latest('created_at')
            ->paginate(50);

        return view(
            'simulation.index',
            [
                'simulations' => $simulations
                    ->transform(function (Simulation $simulation) {
                        return [
                            'id' => $simulation->id,
                            'ticker_symbol' => $simulation->ticker->symbol,
                            'long_profit' => number_format($simulation->long_profit, 2),
                            'short_profit' => number_format($simulation->short_profit, 2),
                            'total_profit' => number_format($simulation->total_profit, 2),
                            'created_at' => $simulation->created_at->format('M d, Y (h:i a)'),
                        ];
                    }),
                'links' =>  $simulations->links(),
            ]
        );
    }

    public function show(Simulation $simulation)
    {
        $days = $simulation
            ->days()
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
                    'ticker' => $simulation->ticker(),
                    'total_long_profit' => number_format($simulation->total_long_profit, 2),
                    'total_short_profit' => number_format($simulation->total_short_profit, 2),
                    'subtotal' => number_format($simulation->total_profit, 2),
                ],
                'links' =>  $days->links(),
            ]
        );
    }
}
