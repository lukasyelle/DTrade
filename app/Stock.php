<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model {

    public function ticker()
    {
        return $this->belongsTo(Ticker::class);
    }

    public function trends()
    {
        return $this->hasMany(StockTrend::class);
    }

    public function portfolios()
    {
        return $this->belongsToMany(Portfolio::class);
    }

}
