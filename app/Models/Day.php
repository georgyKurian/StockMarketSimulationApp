<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Day extends Model
{
    use HasFactory;

    public function candleSticks()
    {
        return $this
            ->hasMany(CandleStick::class, 'day_index', 'day_index')
            ->orderByTime();
    }
}
