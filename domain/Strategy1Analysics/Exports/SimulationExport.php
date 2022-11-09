<?php

namespace Domain\Strategy1Analysics\Exports;

use App\Models\Day;
use App\Models\CandleStick;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
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
            '#',
            'User',
            'Date',
        ];
    }
}
