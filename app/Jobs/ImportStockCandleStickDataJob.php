<?php

namespace App\Jobs;

use App\Models\CandleStick;
use App\Models\Ticker;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimitedWithRedis;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Services\MarketDataService\Client;
use Services\MarketDataService\DataTransferObjects\StockCandleStickData;

class ImportStockCandleStickDataJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 0;
    public $maxExceptions = 3;

    public function __construct(private Ticker $ticker, private Carbon $from, private Carbon $to)
    {
    }

    public function retryUntil()
    {
        return now()->addHour();
    }

    public function middleware()
    {
        return [new RateLimitedWithRedis('polygon_io')];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Client $client)
    {
        $stockAggregateData = $client
            ->getStockAggregates(
                ticker: $this->ticker,
                multiplier:15,
                timespan:'minute',
                from: $this->from,
                to: $this->to
            );

        $stockAggregateData
            ->stockCandleStickDataCollection
            ->each(function (StockCandleStickData $dataBlock) use ($stockAggregateData) {
                //if ($this->isDuringTradeHours($dataBlock->startTime)) {
                CandleStick::updateOrCreate([
                    'ticker_id' => $stockAggregateData->ticker->id,
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
                //}
            });
    }

    public function isDuringTradeHours(Carbon $dateTime)
    {
        return
            ($dateTime->hour > 9 && $dateTime->hour < 16)
            || ($dateTime->hour == 9 && $dateTime->minute >= 30);
    }
}
