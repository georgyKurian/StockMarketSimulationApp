<?php

namespace Services\MarketDataService\PolygonIo;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use PolygonIO\Rest\Rest;
use Services\MarketDataService\Client;
use Services\MarketDataService\DataTransferObjects\StockCandleStickData;

class PolygonClient extends Client
{
    private Rest $rest;

    public function __construct()
    {
        $this->rest = new Rest(Config::get('market-data-service.polygon_io.key'));
    }

    public function getStockAggregates(String $tickerSymbol, int $multiplier, String $timespan, Carbon $from, Carbon $to)
    {
        throw_if($from->greaterThanOrEqualTo($to), new Exception('From date should be less than to date'));

        /** @var Collection<StockCandleStickData> */
        $candleStickCollection = collect();

        $responseData = $this
            ->rest
            ->stocks()
            ->aggregates()
            ->get(
                $tickerSymbol,
                multiplier:$multiplier,
                timespan:$timespan,
                from:$from->toDateString(),
                to:$to->toDateString(),
                params:['limit' => 50000],
            );

        if ($responseData['results']) {
            foreach ($responseData['results'] as $candleStickData) {
                $candleStickCollection->add(
                    new StockCandleStickData(
                        startTimestamp: $candleStickData['t'],
                        open: $candleStickData['o'],
                        high: $candleStickData['h'],
                        low: $candleStickData['l'],
                        close: $candleStickData['c'],
                        volume: $candleStickData['v'],
                        numberOfTransactions: array_key_exists('n', $candleStickData) ? $candleStickData['n'] : null,
                        volumeWeightedAveragePrice: array_key_exists('vw', $candleStickData) ? $candleStickData['vw'] : null,
                    )
                );
            }
        }

        return $candleStickCollection;
    }
}
