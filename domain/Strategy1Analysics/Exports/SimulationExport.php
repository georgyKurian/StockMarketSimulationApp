<?php

namespace Domain\Strategy1Analysics\Exports;

use App\Models\CandleStick;
use App\Models\Day;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SimulationExport implements FromQuery, WithHeadings
{
    use Exportable;

    public function query()
    {
        return Day::query()->orderBy('day_index')->orderBy('time');
    }

    public function headings(): array
    {
        return [
            'Day',
            'Time',
            'High',
            'Low',
            'Long Enter',
            'Long Exit',
            'Short Enter',
            'Short Exit',
        ];
    }

    public function map(Day $day): array
    {
        return $day
            ->candleSticks()
            ->map(function (CandleStick $candleStick) use ($day) {
                return [
                    $candleStick->day_index,
                    $candleStick->time,
                    $candleStick->high,
                    $candleStick->low,
                    $candleStick->id === $day->long_end_at_candle_stick_id ? $day->long_enter_at_price : null,
                    $candleStick->id === $day->long_exit_at_candle_stick_id ? $day->long_exit_at_price : null,
                    $candleStick->id === $day->short_end_at_candle_stick_id ? $day->short_enter_at_price : null,
                    $candleStick->id === $day->short_exit_at_candle_stick_id ? $day->short_exit_at_price : null,
                ];
            });
    }
}
