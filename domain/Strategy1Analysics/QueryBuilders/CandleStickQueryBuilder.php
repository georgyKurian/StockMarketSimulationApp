<?php

namespace Domain\Strategy1Analysics\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class CandleStickQueryBuilder extends Builder
{
    public function orderByTime()
    {
        return $this->orderBy('time');
    }

    public function orderByDay()
    {
        return $this->orderBy('day_index');
    }

    public function orderByDayAndTime()
    {
        return $this
            ->orderByDay()
            ->orderByTime();
    }
}
