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
}
