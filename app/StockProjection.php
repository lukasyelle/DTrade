<?php

namespace App;

use App\Events\StockUpdated;
use App\Support\Database\CacheQueryBuilder;
use App\Traits\KellySizing;
use Illuminate\Database\Eloquent\Model;

class StockProjection extends Model
{
    use CacheQueryBuilder;
    use KellySizing;

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

    protected $appends = [
        'probabilityProfit',
        'probabilityLoss',
        'kellyPositionSize',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function getBroadOutcome(string $outcome)
    {
        $probabilities = collect();
        collect($this->fillable)->filter(function ($column) use ($outcome) {
            return strpos($column, $outcome) > -1;
        })->each(function ($column) use (&$probabilities) {
            $probabilities[$column] = $this->$column;
        });

        return $probabilities;
    }

    public function probabilityBroadOutcome(string $outcome)
    {
        $probabilityOutcome = 0.0;
        $this->getBroadOutcome($outcome)->each(function ($probability) use (&$probabilityOutcome) {
            $probabilityOutcome += $probability;
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

    public static function makeFor(Stock $stock)
    {
        $projections = collect([
            'next day'  => collect($stock->nextDayProjection()),
            'five day'  => collect($stock->fiveDayProjection()),
            'ten day'   => collect($stock->tenDayProjection()),
        ]);
        $projections->each(function ($projectionData, $projectionFor) use ($stock) {
            $stockProjection = [
                'stock_id'          => $stock->id,
                'projection_for'    => $projectionFor,
            ];
            $projectionData->each(function ($item, $key) use (&$stockProjection) {
                $key = ($key == 'verdict') ? $key : 'probability_'.str_replace(' ', '_', $key);
                $stockProjection[$key] = $item;
            });
            self::create($stockProjection);
        });
        event(new StockUpdated($stock, 'Stock projections have been updated successfully.'));
    }
}
