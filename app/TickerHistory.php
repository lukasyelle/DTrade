<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TickerHistory extends Model
{

    public function ticker()
    {
        return $this->belongsTo(Ticker::class);
    }

}
