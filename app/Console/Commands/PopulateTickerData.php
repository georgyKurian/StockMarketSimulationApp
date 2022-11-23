<?php

namespace App\Console\Commands;

use App\Models\Ticker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class PopulateTickerData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add ticker data from config and fetch the candle stick data from API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tickerSymbols = Config::get('stock.tickers');

        collect($tickerSymbols)
            ->each(function (String $symbol) {
                Ticker::updateOrCreate(['symbol' => $symbol]);
                Artisan::call('import-data', [
                    'tickerSymbol' => $symbol,
                ]);
            });

        return Command::SUCCESS;
    }
}
