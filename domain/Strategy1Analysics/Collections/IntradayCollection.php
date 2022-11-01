<?php

namespace Domain\Strategy1Analysics\Collections;

use App\Models\Intraday;
use Illuminate\Database\Eloquent\Collection;

class IntradayCollection extends Collection
{
    public function findCandleStickByTime(int $time): ?Intraday
    {
        return $this->firstWhere(fn (Intraday $intraday) => $intraday->firstWhere('time', 1000));
    }

    /**
     * Filter between fromTime and toTime exclusive fo fromTime and toTime
     */
    public function filterCandleSticksBetweenTime(int $fromTime, int $toTime): self
    {
        return $this->where(fn (Intraday $intraday) => $fromTime < $intraday->time && $intraday->time < $toTime);
    }

    public function orderByTime()
    {
        return $this->sortBy('time');
    }
}
