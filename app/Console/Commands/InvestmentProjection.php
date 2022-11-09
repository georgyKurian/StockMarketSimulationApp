<?php

namespace App\Console\Commands;

use App\Models\CandleStick;
use App\Services\PolygonClient;
use Domain\Strategy1Analysics\Actions\DaySimulation;
use Illuminate\Console\Command;

class InvestmentProjection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'investment-projection:start {percentage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Project investment based on percentage risk.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $riskFactor = $this->argument('percentage');

        CandleStick::query()
            ->distinct()
            ->orderBy('day_index')
            ->get(['day_index'])
            ->each(function (CandleStick $intradayIndex) use ($riskFactor) {
                $intradayPoints = CandleStick::where('day_index', $intradayIndex->day_index)
                    ->where('time', '>=', 1000)
                    ->oldest('recorded_at')
                    ->get();

                (new DaySimulation($intradayIndex->day_index, $intradayPoints))->execute($riskFactor);
            });


        return Command::SUCCESS;
    }
}
