<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    public function stocks()
    {
        return $this->belongsToMany(Stock::class);
    }
}
