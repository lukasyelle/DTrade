<?php

namespace App;

use App\Traits\StockIndicators;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use StockIndicators;

    protected $fillable = ['ticker_id'];
    protected $appends = ['symbol', 'data', 'lastUpdate', 'value', 'projections'];

    public function ticker()
    {
        return $this->belongsTo(Ticker::class);
    }

    public function projections()
    {
        return $this->hasMany(StockProjection::class);
    }

    public function trainedModels()
    {
        return $this->hasMany(TrainedStockModel::class);
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
        return $this->data->last();
    }

    public function getValueAttribute()
    {
        return $this->lastUpdate->close;
    }

    public function getLastTrainedModel()
    {
        $model = $this->trainedModels->last();
        if ($model !== null && $model instanceof TrainedStockModel) {
            $modelTrainedAt = $model->created_at;
            $lastDataPointTakenAt = $this->data->last()->created_at;
            if ($modelTrainedAt->diffInHours($lastDataPointTakenAt) >= 23) {
                // Used a cached model only if you are analyzing the stock more
                // than once a day. Otherwise train a new model on up to date
                // data.
                return;
            }

            return $model->retrieve();
        }
    }
}
