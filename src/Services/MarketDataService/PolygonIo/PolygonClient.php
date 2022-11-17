<?php

namespace Services\MarketDataService\PolygonIo;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Services\MarketDataService\Client;
use Services\MarketDataService\DataTransferObjects\StockCandleStickData;

class PolygonClient extends Client
{
    private PendingRequest $client;
    private Response $response;

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

        $this->response = $this
            ->client
            ->get("v2/aggs/ticker/{$tickerSymbol}/range/{$multiplier}/{$timespan}/{$from->toDateString()}/{$to->toDateString()}", [
                'adjusted' =>true,
                'sort'=>'asc',
                'limit' => 50000,
            ]);

        return $this
            ->checkResponseStatus()
            ->parseResponseBody();
    }

    private function checkResponseStatus(): self
    {
        if ($this->response->failed() && $this->response->status() == 429) {
            throw new Exception('Too many requests in a minute');
        }

        if (! $this->response->ok()) {
            Log::withContext([
                'responseStatus' => $this->response->status(),
                'responseBody' => $this->response->body(),
            ]);
            throw new Exception('Request failed');
        }

        return $this;
    }

    /**
     * @return Collection<StockCandleStickData>
     */
    private function parseResponseBody()
    {
        /** @var Collection<StockCandleStickData> */
        $candleStickCollection = collect();
        $this->responseData = json_decode($this->response->body());

        if (! $this->responseData?->results) {
            Log::withContext(['responseBody' => $this->response->body()]);
            throw new Exception('No result in the response');
        }

        foreach ($this->responseData->results as $candleStickData) {
            Log::withContext([$candleStickData]);

            $candleStickCollection->add(
                new StockCandleStickData(
                    startTimestamp: $candleStickData->t,
                    open: $candleStickData->o,
                    high: $candleStickData->h,
                    low: $candleStickData->l,
                    close: $candleStickData->c,
                    volume: $candleStickData->v,
                    numberOfTransactions: property_exists($candleStickData, 'n') ? $candleStickData->n : null,
                    volumeWeightedAveragePrice: property_exists($candleStickData, 'vw') ? $candleStickData->vw : null,
                )
            );
        }

        return $candleStickCollection;
    }
}
