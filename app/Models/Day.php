<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Day extends Model
{
    use HasFactory;

    public function ticker()
    {
        return $this->belongsTo(Ticker::class);
    }

    public function simulation()
    {
        return $this->belongsTo(Simulation::class);
    }

    public function candleSticks()
    {
        return $this
            ->hasMany(CandleStick::class, 'day_index', 'day_index')
            ->orderByTime();
    }

    public function longStartAtCandleStick()
    {
        return $this->belongsTo(CandleStick::class);
    }

    public function longEndAtCandleStick()
    {
        return $this->belongsTo(CandleStick::class);
    }

    public function shortStartAtCandleStick()
    {
        return $this->belongsTo(CandleStick::class);
    }

    public function shortEndAtCandleStick()
    {
        return $this->belongsTo(CandleStick::class);
    }
}
