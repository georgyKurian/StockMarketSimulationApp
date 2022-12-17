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
    protected $signature = 'project {tickerSymbol}';

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
        $tickerSymbol = $this->argument('tickerSymbol');
        $ticker = Ticker::findOrFailBySymbol($tickerSymbol);

        for ($threshold = -10; $threshold <= 300; $threshold = $threshold + 2) {
            $simulationAction->execute($ticker, $threshold);
        }

        return Command::SUCCESS;
    }
}
