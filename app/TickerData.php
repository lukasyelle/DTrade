<?php

namespace App;

use App\Support\Database\CacheQueryBuilder;
use Illuminate\Database\Eloquent\Model;

class TickerData extends Model
{
    use CacheQueryBuilder;

    protected $fillable = ['ticker_id', 'open', 'high', 'low', 'close', 'previous_close', 'change', 'change_percent', 'volume'];

    public function ticker()
    {
        return $this->belongsTo(Ticker::class);
    }

    public function stock()
    {
        return $this->ticker->stock();
    }
}
