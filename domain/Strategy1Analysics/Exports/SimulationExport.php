<?php

namespace Domain\Strategy1Analysics\Exports;

use App\Models\Day;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class SimulationExport implements FromCollection
{
    use Exportable;

    public function collection()
    {
        return Day::all();
    }
}
