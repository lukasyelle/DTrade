<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $appends = ['value'];
    protected $fillable = ['user_id', 'platform_data_id'];

    public function getValueAttribute()
    {
        $stocks = $this->stocks;

        return $stocks->value * $stocks->pivot->shares;
    }

    public function stocks()
    {
        return $this->belongsToMany(Stock::class)->withPivot('shares');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
