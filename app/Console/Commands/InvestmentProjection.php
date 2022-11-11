<?php

namespace App\Console\Commands;

use App\Models\CandleStick;
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
            ->pluck('day_index')
            ->each(function (int $dayIndex) use ($riskFactor) {
                $candleSticks = CandleStick::where('day_index', $dayIndex)
                    ->where('time', '>=', 1000)
                    ->orderByTime()
                    ->get();

                (new DaySimulation($dayIndex, $candleSticks))->execute($riskFactor);
            });

        return Command::SUCCESS;
    }
}
