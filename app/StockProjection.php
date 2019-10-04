<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockProjection extends Model
{
    protected $fillable = [
        'stock_id',
        'projection_for',
        'verdict',
        'probability_large_loss',
        'probability_moderate_loss',
        'probability_small_loss',
        'probability_large_profit',
        'probability_moderate_profit',
        'probability_small_profit',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
