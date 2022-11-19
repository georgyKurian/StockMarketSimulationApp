<?php

namespace App\Console\Commands;

use App\Models\Ticker;
use Domain\Stock\Actions\SimulationAction;
use Illuminate\Console\Command;

class InvestmentProjection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'investment-projection:start {tickerSymbol} {threshold}';

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
    public function handle(SimulationAction $simulationAction)
    {
        $threshold = $this->argument('threshold');
        $tickerSymbol = $this->argument('tickerSymbol');
        $ticker = Ticker::findOrFailBySymbol($tickerSymbol);

        $simulationAction->execute($ticker, $threshold);

        return Command::SUCCESS;
    }
}
