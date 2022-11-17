<?php

namespace App\Jobs;

use App\Models\CandleStick;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Services\MarketDataService\Client;
use Services\MarketDataService\DataTransferObjects\StockCandleStickData;

class ImportStockCandleStickDataJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private Carbon $from, private Carbon $to)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Client $client)
    {
        $dataCollection = $this
            ->client
            ->getStockAggregates(
                tickerSymbol: 'VOO',
                multiplier:15,
                timespan:'minute',
                from: $this->from,
                to: $this->to
            );

        $dataCollection
            ->each(function (StockCandleStickData $dataBlock) {
                if ($this->isDuringTradeHours($dataBlock->startTime)) {
                    CandleStick::updateOrCreate([
                        'day_index' => $dataBlock->startTime->isoFormat('YMMDD'),
                        'time' => $dataBlock->startTime->isoFormat('HHmm'),
                        'open' => $dataBlock->open,
                        'high' => $dataBlock->high,
                        'low' => $dataBlock->low,
                        'close' => $dataBlock->close,
                        'volume' => $dataBlock->volume,
                        'vw_avg_price' => $dataBlock->volumeWeightedAveragePrice,
                        'recorded_at' => $dataBlock->startTime,
                    ]);
                }
            });
    }

    public function isDuringTradeHours(Carbon $dateTime)
    {
        return
            ($dateTime->hour > 9 && $dateTime->hour < 16)
            || ($dateTime->hour == 9 && $dateTime->minute >= 30);
    }
}
