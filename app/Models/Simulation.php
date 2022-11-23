<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Simulation extends Model
{
    use HasFactory;

    public function days()
    {
        return $this->hasMany(Day::class);
    }

    public function candleSticks()
    {
        return $this->hasMany(CandleStick::class, 'ticker_id', 'ticker_id');
    }

    public function ticker()
    {
        return $this->belongsTo(Ticker::class);
    }

    public function dateRange()
    {
        /** type @var Carbon */
        $fromDate = null;
        /** type @var Carbon */
        $toDate = null;

        $from = $this->days()->orderBy('day_index')->first()->day_index;
        $to = $this->days()->orderByDesc('day_index')->first()->day_index;

        if ($from) {
            $fromDate = Carbon::createFromIsoFormat('YMMDD', $from);
        }

        if ($to) {
            $toDate = Carbon::createFromIsoFormat('YMMDD', $to);
        }

        return $fromDate?->format('M d, Y').' - '.$toDate?->format('M d, Y')." ({$fromDate->diffInMonths($toDate)} Months)";
    }
}
