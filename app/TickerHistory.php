<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TickerHistory extends Model
{

    protected $fillable = ['ticker_id', 'data', 'as_of'];

    public function ticker()
    {
        return $this->belongsTo(Ticker::class);
    }

}
