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

    public function orderByTime()
    {
        return $this->sortBy('time');
    }

    public function orderByDay()
    {
        return $this->sortBy('day_index');
    }

    public function orderByDayAndTime()
    {
        return $this
            ->orderByDay()
            ->orderByTime();
    }
}