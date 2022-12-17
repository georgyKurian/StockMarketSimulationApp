<?php

namespace App\Http\Controllers;

use App\Models\CandleStick;
use App\Models\Day;
use Illuminate\Support\Carbon;

class DayController extends Controller
{
    public function show(Day $day)
    {
        return view(
            'days.show',
            [
                'candleSticks' => $day
                    ->candleSticks()
                    ->onlyNormalMarketHours()
                    ->get()
                    ->map(function (CandleStick $candleStick) use ($day) {
                        return [
                            'id' => $candleStick->id,
                            'time' => $candleStick->recorded_at->format('h:i A'),
                            'open' => convert_cents_to_dollar_string($candleStick->open),
                            'close' => convert_cents_to_dollar_string($candleStick->close),
                            'high' => convert_cents_to_dollar_string($candleStick->high),
                            'low' => convert_cents_to_dollar_string($candleStick->low),
                            'long_enter_at' => $candleStick->id === $day->long_start_at_candle_stick_id ? convert_cents_to_dollar_string($day->long_enter_at_price) : null,
                            'long_exit_at' => $candleStick->id === $day->long_end_at_candle_stick_id ? convert_cents_to_dollar_string($day->long_exit_at_price) : null,
                            'short_enter_at' => $candleStick->id === $day->short_start_at_candle_stick_id ? convert_cents_to_dollar_string($day->short_enter_at_price) : null,
                            'short_exit_at' => $candleStick->id === $day->short_end_at_candle_stick_id ? convert_cents_to_dollar_string($day->short_exit_at_price) : null,
                        ];
                    }),
                'summary' => [
                    'date' => Carbon::createFromIsoFormat('YMMDD', 20211021)->toDateString(),
                    'long_profit' => convert_cents_to_dollar_string($day->long_profit),
                    'short_profit' => convert_cents_to_dollar_string($day->short_profit),
                    'total_profit' => convert_cents_to_dollar_string($day->total_profit),
                ],
                'links' =>  '',
            ]
        );
    }
}
