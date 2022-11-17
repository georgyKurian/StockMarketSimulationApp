<?php

namespace Services\MarketDataService\PolygonIo;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use PolygonIO\Rest\Rest;
use Services\MarketDataService\Client;
use Services\MarketDataService\DataTransferObjects\StockCandleStickData;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class PolygonClient extends Client
{
    private PendingRequest $client;
    private const BASE_URL = 'https://api.polygon.io/';

    public function __construct()
    {
        $this->client = Http::acceptJson()
            ->baseUrl(self::BASE_URL)
            ->timeout(60)
            ->withToken(Config::get('market-data-service.polygon_io.key'));
    }

    public function getStockAggregates(String $tickerSymbol, int $multiplier, String $timespan, Carbon $from, Carbon $to)
    {
        throw_if($from->greaterThanOrEqualTo($to), new Exception('From date should be less than to date'));

        /** @var Collection<StockCandleStickData> */
        $candleStickCollection = collect();

        $response = $this
            ->client
            ->get("v2/aggs/ticker/{$tickerSymbol}/range/{$multiplier}/{$timespan}/{$from->toDateString()}/{$to->toDateString()}",[
                'adjusted' =>true,
                'sort'=>'asc',
                'limit' => 50000
            ]);
            
        
        if ($response->failed() && $response->status() == 429) {
            throw new Exception('Too many requests in a minute');
        }

        if($response->ok()){
            $responseData = json_decode($response->body());

            if($responseData && is_array($responseData) && array_key_exists('results',$responseData)){
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
        }
           

        return $candleStickCollection;
    }
}
