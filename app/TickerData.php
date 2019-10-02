<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TickerData extends Model
{
    protected $fillable = ['ticker_id', 'high', 'low', 'open', 'close', 'previous_close', 'change', 'change_percent', 'volume'];

    public function ticker()
    {
        return $this->belongsTo(Ticker::class);
    }
}
