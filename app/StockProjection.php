<?php

namespace App;

use App\Support\Database\CacheQueryBuilder;
use Illuminate\Database\Eloquent\Model;

class StockProjection extends Model
{
    use CacheQueryBuilder;

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

    protected $appends = ['probabilityProfit', 'probabilityLoss'];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function probabilityBroadOutcome(string $outcome)
    {
        $probabilityOutcome = 0.0;
        collect($this->fillable)->filter(function ($column) use ($outcome) {
            return strpos($column, $outcome) > -1;
        })->each(function ($column) use (&$probabilityOutcome) {
            $probabilityOutcome += $this->$column;
        });

        return $probabilityOutcome;
    }

    public function getProbabilityLossAttribute()
    {
        return $this->probabilityBroadOutcome('loss');
    }

    public function getProbabilityProfitAttribute()
    {
        return $this->probabilityBroadOutcome('profit');
    }
}
