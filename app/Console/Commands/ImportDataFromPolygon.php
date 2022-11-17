<?php

namespace App\Console\Commands;

use App\Jobs\ImportStockCandleStickDataJob;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ImportDataFromPolygon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'polygon:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from polygon.ai';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fromDate = Carbon::parse('2021-10-10');
        $toDate = Carbon::parse('2022-10-10');

        $loopFromDate = $fromDate->copy();
        $loopToDate = $fromDate->copy()->addMonths(3);

        while ($loopFromDate->lessThan($toDate)) {
            if ($loopToDate->greaterThan($toDate)) {
                $loopToDate = $toDate->copy();
            }

            ImportStockCandleStickDataJob::dispatch($loopFromDate, $loopToDate);

            dump($loopFromDate->toDateTimeString());
            $loopFromDate = $loopToDate->copy();
            $loopToDate = $loopToDate->copy()->addMonths(3);
        }

        return Command::SUCCESS;
    }
}
