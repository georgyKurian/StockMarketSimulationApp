<?php

namespace App\Console\Commands;

use App\Jobs\ImportStockCandleStickDataJob;
use App\Models\Ticker;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ImportDataFromPolygon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-data {tickerSymbol}';

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
        $tickerSymbol = $this->argument('tickerSymbol');
        $ticker = Ticker::findOrFailBySymbol($tickerSymbol);

        $fromDate = Carbon::today()->subYears(2);
        $toDate = Carbon::yesterday();

        $loopFromDate = $fromDate->copy();
        $loopToDate = $fromDate->copy()->addMonths(3);

        while ($loopFromDate->lessThan($toDate)) {
            if ($loopToDate->greaterThan($toDate)) {
                $loopToDate = $toDate->copy();
            }

            ImportStockCandleStickDataJob::dispatch($ticker, $loopFromDate, $loopToDate);

            $loopFromDate = $loopToDate->copy();
            $loopToDate = $loopToDate->copy()->addMonths(3);
        }

        $this->info('Started importing!');

        return Command::SUCCESS;
    }
}
