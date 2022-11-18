<?php

namespace App\Http\Controllers;

use App\Models\CandleStick;
use App\Models\Day;
use Illuminate\Support\Carbon;

class DayController extends Controller
{
    public function index()
    {
        $daysPaginate = Day::query()
            ->orderBy('day_index')
            ->paginate(50);

        $simulationSummary = Day::query()
            ->selectRaw('SUM(long_profit) as total_long_profit, SUM(short_profit) as total_short_profit')
            ->first();

        return view(
            'days.index',
            [
                'days' => $daysPaginate
                    ->transform(function (Day $day) {
                        return [
                            'id' => $day->id,
                            'date' => Carbon::createFromIsoFormat('YMMDD', $day->day_index)->toDateString(),
                            'long_profit' => number_format($day->long_profit, 2),
                            'short_profit' => number_format($day->short_profit, 2),
                            'total_profit' => number_format($day->total_profit, 2),
                        ];
                    }),
                'summary' => [
                    'total_long_profit' => number_format($simulationSummary->total_long_profit, 2),
                    'total_short_profit' => number_format($simulationSummary->total_short_profit, 2),
                    'subtotal' => number_format($simulationSummary->total_long_profit + $simulationSummary->total_short_profit, 2),
                ],
                'links' =>  $daysPaginate->links(),
            ]
        );
    }

    public function show(Day $day)
    {
        return view(
            'day',
            [
                'candleSticks' => $day->candleSticks->map(function (CandleStick $candleStick) use ($day) {
                    return [
                        'id' => $candleStick->id,
                        'time' => $candleStick->recorded_at->format('h:i A'),
                        'open' => number_format($candleStick->open, 2),
                        'close' => number_format($candleStick->close, 2),
                        'high' => number_format($candleStick->high, 2),
                        'low' => number_format($candleStick->low, 2),
                        'long_enter_at' => $candleStick->id === $day->long_start_at_candle_stick_id ? number_format($day->long_enter_at_price, 2) : null,
                        'long_exit_at' => $candleStick->id === $day->long_end_at_candle_stick_id ? number_format($day->long_exit_at_price, 2) : null,
                        'short_enter_at' => $candleStick->id === $day->short_start_at_candle_stick_id ? number_format($day->short_enter_at_price, 2) : null,
                        'short_exit_at' => $candleStick->id === $day->short_end_at_candle_stick_id ? number_format($day->short_exit_at_price, 2) : null,
                    ];
                }),
                'summary' => [
                    'date' => Carbon::createFromIsoFormat('YMMDD', 20211021)->toDateString(),
                    'long_profit' => number_format($day->long_profit, 2),
                    'short_profit' => number_format($day->short_profit, 2),
                    'total_profit' => number_format($day->total_profit, 2),
                ],
                'links' =>  '',
            ]
        );
    }
}
