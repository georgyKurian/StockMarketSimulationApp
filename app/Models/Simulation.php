<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        $from = $this->days()->oldest('day')->first()->day;
        $to = $this->days()->latest('day')->first()->day;

        return $from?->format('M d, Y').' - '.$to?->format('M d, Y')." ({$from->diffInMonths($to)} Months)";
    }
}
