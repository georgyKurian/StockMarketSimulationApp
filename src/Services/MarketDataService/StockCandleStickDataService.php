<?php

namespace Services\MarketDataService;

use Illuminate\Support;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class StockCandleStickDataService
{
    private Carbon $pageFromDate;
    private Carbon $fromDate;
    private Carbon $toDate;

    private String $tickerSymbol;
    private int $multiplier;
    private String $timespan;

   private Carbon $lastDateFetched;

    public function __construct(private Client $client)
    {
    }

    public function initialize(String $tickerSymbol, int $multiplier, String $timespan, Carbon $from, Carbon $to)
    {
        $this->tickerSymbol = $tickerSymbol;
        $this->multiplier = $multiplier;
        $this->timespan = $timespan;
        $this->fromDate = $from;
        $this->pageFromDate = $from->copy();
        $this->toDate = $to;
    }

    /**
     * 
     * @return Collection<StockCandleStickData> 
     */
    public function getData()
    {
        $data = $this
            ->client
            ->getStockAggregates($this->tickerSymbol, $this->multiplier, $this->timespan, $this->currentPageFrom, $this->to);

        $this->lastDateFetched = $data->last()?->startTimestamp ?? $this->to->copy();

        return $data;
    }

    public function hasNextPage()
    {
        return $this->toDate->greaterThan($this->lastDateFetched);
    }

    public function nextPage()
    {
        if($this->lastDateFetched->lessThanOrEqualTo($this->toDate)){
            $nextFromDate = $this->lastDateFetched->copy();
        }
    }
}