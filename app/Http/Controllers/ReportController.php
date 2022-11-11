<?php

namespace App\Http\Controllers;

use App\Models\CandleStick;
use App\Models\Day;

class ReportController extends Controller
{
    public function index()
    {
        /** @var Day */
        $day = Day::query()
            ->with(['candleSticks'])
            ->orderBy('day_index')
            ->paginate(1)
            ?->first();

        return view(
            'index',
            [
                'candleSticks' => $day->candleSticks->map(function (CandleStick $candleStick) use ($day) {
                    return [
                        'day_index' => $day->day_index,
                        'id' => $candleStick->id,
                        'time' => $candleStick->time,
                        'high' => $candleStick->high,
                        'low' => $candleStick->low,
                        'long_enter_at' => $candleStick->id === $day->long_start_at_candle_stick_id ? $day->long_enter_at_price : null,
                        'long_exit_at' => $candleStick->id === $day->long_end_at_candle_stick_id ? $day->long_exit_at_price : null,
                        'short_enter_at' => $candleStick->id === $day->short_start_at_candle_stick_id ? $day->short_enter_at_price : null,
                        'short_exit_at' => $candleStick->id === $day->short_end_at_candle_stick_id ? $day->short_exit_at_price : null,
                    ];
                }),
            ]
        );
    }
}
