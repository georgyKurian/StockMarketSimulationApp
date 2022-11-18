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
    protected $signature = 'investment-projection:start {threshold}';

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
        $threshold = $this->argument('threshold');

        CandleStick::query()
            ->select('day_index')
            ->distinct()
            ->orderBy('day_index')
            ->each(function (CandleStick $candleStick, $key) use ($threshold) {
                $candleSticks = CandleStick::where('day_index', $candleStick->day_index)
                    ->where('time', '>=', 1000)
                    ->whereNotNull('volume')
                    ->orderByTime()
                    ->get();

                (new DaySimulation($candleStick->day_index, $candleSticks))->execute($threshold);
            });

        return Command::SUCCESS;
    }
}
