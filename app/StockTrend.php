<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockTrend extends Model
{
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
