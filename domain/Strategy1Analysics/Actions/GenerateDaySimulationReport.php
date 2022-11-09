<?php

namespace Domain\Strategy1Analysics\Actions;

use App\Models\Day;
use App\Models\CandleStick;
use Domain\Strategy1Analysics\Collections\CandleStickCollection;
use Domain\Strategy1Analysics\Exports\SimulationExport;

class GenerateDaySimulationReport
{
    public function execute()
    {
        (new SimulationExport())->store('reports/'.now()->timestamp.'.xlsx');
    }
}
