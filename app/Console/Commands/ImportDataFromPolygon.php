<?php

namespace App\Console\Commands;

use App\Models\Intraday;
use App\Services\PolygonClient;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

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
    public function handle(PolygonClient $polygonClient)
    {
        $data = $polygonClient->getStockAggregates("VOO", multiplier:15, timespan:'minute', from:new Carbon('2021-10-20'), to:new Carbon('2022-10-20'));

        collect($data['results'])
            ->each(function ($dataBlock) {
                $recordedAt = new Carbon($dataBlock['t']/1000);

                if ($this->isDuringTradeHours($recordedAt)) {
                    Intraday::create([
                        'day_index' => $recordedAt->isoFormat('YMMDD'),
                        'time' => $recordedAt->isoFormat('HHmm'),
                        'open' => $dataBlock['o'],
                        'high' => $dataBlock['h'],
                        'low' => $dataBlock['l'],
                        'close' => $dataBlock['c'],
                        'volume' => $dataBlock['v'],
                        'vw_avg_price' => $dataBlock['vw'],
                        'recorded_at' => $recordedAt,
                    ]);
                }
            });

        return Command::SUCCESS;
    }

    public function isDuringTradeHours(Carbon $dateTime)
    {
        return (
            ($dateTime->hour > 9 && $dateTime->hour < 16) || ($dateTime->hour == 9 && $dateTime->minute >= 30)
        );
    }
}
