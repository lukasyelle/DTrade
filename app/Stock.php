<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['ticker_id'];
    protected $appends = ['value', 'data'];

    public function getValueAttribute()
    {
        return $this->ticker->data->first()->close;
    }

    public function getDataAttribute()
    {
        return $this->ticker->data;
    }

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
