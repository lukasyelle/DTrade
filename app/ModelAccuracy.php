<?php

namespace App;

use App\Support\Database\CacheQueryBuilder;
use Illuminate\Database\Eloquent\Model;

class ModelAccuracy extends Model
{
    use CacheQueryBuilder;

    protected $fillable = [
        'stock_id',
        'time_period',
        'duration',
        'accuracy_large_loss',
        'accuracy_moderate_loss',
        'accuracy_small_loss',
        'accuracy_large_profit',
        'accuracy_moderate_profit',
        'accuracy_small_profit',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
