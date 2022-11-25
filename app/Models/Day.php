<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Day extends Model
{
    use HasFactory;

    protected $casts = [
        'day' => 'date',
    ];

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
            ->hasMany(CandleStick::class, 'day', 'day')
            ->where('ticker_id', $this->ticker_id)
            ->orderByDateTimeOldest();
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
