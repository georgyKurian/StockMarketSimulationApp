<?php

namespace Domain\Stock\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class TickerQueryBuilder extends Builder
{
    public function findOrFailBySymbol(String $symbol)
    {
        return $this->where('symbol', $symbol)->firstOrFail();
    }
}
