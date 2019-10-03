<?php

namespace App;

use App\Traits\StockIndicators;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use StockIndicators;

    protected $fillable = ['ticker_id'];
    protected $appends = ['symbol', 'data', 'lastUpdate', 'value'];

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

    public static function fetch($ticker)
    {
        return Ticker::fetch($ticker)->stock;
    }

    public function getSymbolAttribute()
    {
        return $this->ticker['symbol'];
    }

    public function getDataAttribute()
    {
        return $this->ticker->data->take(365)->reverse();
    }

    public function getLastUpdateAttribute()
    {
        return $this->data->first();
    }

    public function getValueAttribute()
    {
        return $this->lastUpdate->close;
    }
}
