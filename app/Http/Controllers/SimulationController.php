<?php

namespace App\Http\Controllers;

use App\Models\Day;
use Illuminate\Support\Carbon;

class SimulationController extends Controller
{
    public function index()
    {
        $days = Day::query()
            ->with(['candleSticks'])
            ->orderBy('day_index')
            ->paginate(50);

        $simulationSummary = Day::query()
            ->selectRaw('SUM(long_profit) as total_long_profit, SUM(short_profit) as total_short_profit, SUM(short_profit) as total_short_profit, SUM(total_profit) as aggregate_profit')
            ->first();

        return view(
            'simulation-result',
            [
                'days' => $days->transform(fn (Day $day) => [
                    'date' => Carbon::createFromIsoFormat('YMMDD', $day->day_index)->toDateString(),
                    'long_profit' => number_format($day->long_profit, 2),
                    'short_profit' => number_format($day->short_profit, 2),
                    'total_profit' => number_format($day->total_profit, 2),
                ]),
                'summary' => [
                    'long_profit' => number_format($simulationSummary->total_long_profit, 2),
                    'short_profit' => number_format($simulationSummary->total_short_profit, 2),
                    'total_profit' => number_format($simulationSummary->aggregate_profit, 2),
                ],
                'links' =>  $days->links(),
            ]
        );
    }
}
