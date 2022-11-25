<?php

namespace Domain\Stock\Collections;

use App\Models\CandleStick;
use Illuminate\Database\Eloquent\Collection;

class CandleStickCollection extends Collection
{
    public function findCandleStickByTime(int $time): ?CandleStick
    {
        return $this->firstWhere(fn (CandleStick $candleStick) => $candleStick->time === $time);
    }

    /**
     * Filter between fromTime and toTime exclusive fo fromTime and toTime.
     */
    public function filterCandleSticksBetweenTime(int $fromTime, int $toTime): self
    {
        return $this->where(fn (CandleStick $candleStick) => $fromTime < $candleStick->time && $candleStick->time < $toTime);
    }

    public function orderByDateTimeOldest()
    {
        return $this->sortBy('recorded_at');
    }
}
