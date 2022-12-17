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
                            'long_profit' => convert_cents_to_dollar_string($day->long_profit),
                            'short_profit' => convert_cents_to_dollar_string($day->short_profit),
                            'total_profit' => convert_cents_to_dollar_string($day->total_profit),
                            'profit_percentage' => convert_to_percentage_string($day->profit_percentage),
                        ]),
                'months' => [
                    'data' => $months->transform(fn (Day $monthAggregate) => [
                        'year' => Carbon::parse($monthAggregate->year)->year,
                        'month' => Carbon::parse($monthAggregate->month)->monthName,
                        'long_profit' => convert_cents_to_dollar_string($monthAggregate->long_profit),
                        'short_profit' => convert_cents_to_dollar_string($monthAggregate->short_profit),
                        'total_profit' => convert_cents_to_dollar_string($monthAggregate->total_profit),
                        'profit_percentage' => convert_to_percentage_string($monthAggregate->profit_percentage),
                    ]),
                    'links' => $months->links(),
                ],
                'simulation' => [
                    'id' => $simulation->id,
                    'ticker' => $simulation->ticker,
                    'threshold' => convert_cents_to_dollar_string($simulation->threshold),
                    'long_profit' => convert_cents_to_dollar_string($simulation->long_profit),
                    'short_profit' => convert_cents_to_dollar_string($simulation->short_profit),
                    'total_profit' => convert_cents_to_dollar_string($simulation->total_profit),
                    'date_range' => $simulation->dateRange(),
                ],
                'links' =>  $days->links(),
            ]
        );
    }
}
