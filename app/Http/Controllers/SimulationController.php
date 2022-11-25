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
            ->latest('day')
            ->paginate(50);

        $months = $simulation
            ->days()
            ->groupByRaw("DATE_TRUNC('year',day), DATE_TRUNC('month', day)")
            ->selectRaw("SUM(long_profit) as long_profit, SUM(short_profit) as short_profit, SUM(total_profit) as total_profit, SUM(profit_percentage) as profit_percentage, DATE_TRUNC('year',day) as year, DATE_TRUNC('month',day) as month")
            ->orderBYRaw('year DESC, month DESC')
            ->paginate(20, ['*'], 'days');

        return view(
            'simulation.show',
            [
                'days' => $days
                    ->transform(fn (Day $day) => [
                            'id' => $day->id,
                            'date' => $day->day->toDateString(),
                            'long_profit' => number_format($day->long_profit, 2),
                            'short_profit' => number_format($day->short_profit, 2),
                            'total_profit' => number_format($day->total_profit, 2),
                            'profit_percentage' => number_format($day->profit_percentage, 2).' %',
                        ]),
                'months' => [
                    'data' => $months->transform(fn (Day $monthAggregate) => [
                        'year' => Carbon::parse($monthAggregate->year)->year,
                        'month' => Carbon::parse($monthAggregate->month)->monthName,
                        'long_profit' => number_format($monthAggregate->long_profit, 2),
                        'short_profit' => number_format($monthAggregate->short_profit, 2),
                        'total_profit' => number_format($monthAggregate->total_profit, 2),
                        'profit_percentage' => number_format($monthAggregate->profit_percentage, 2).' %',
                    ]),
                    'links' => $months->links(),
                ],
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
