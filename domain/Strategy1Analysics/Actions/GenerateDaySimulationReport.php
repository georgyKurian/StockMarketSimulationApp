<?php

namespace Domain\Strategy1Analysics\Actions;

use Domain\Strategy1Analysics\Exports\SimulationExport;

class GenerateDaySimulationReport
{
    public function execute()
    {
        (new SimulationExport())->store('reports/'.now()->timestamp.'.xlsx');
    }
}
