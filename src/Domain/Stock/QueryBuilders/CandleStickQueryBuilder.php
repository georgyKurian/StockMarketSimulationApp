<?php

namespace Domain\Stock\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class CandleStickQueryBuilder extends Builder
{
    public function orderByDateTimeOldest(): self
    {
        return $this->oldest('recorded_at');
    }

    public function orderByDateTimeLatest(): self
    {
        return $this->latest('recorded_at');
    }

    public function onlyNormalMarketHours(): self
    {
        $startAt = config('stock.market_hours.start') ?? 930;
        $endAt = config('stock.market_hours.end') ?? 1600;

        return $this->whereBetween('time', [$startAt, $endAt]);
    }
}
