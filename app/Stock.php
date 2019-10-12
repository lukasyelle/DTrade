<?php

namespace App;

use App\Traits\StockAnalysis;
use App\Traits\StockIndicators;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Stock extends Model
{
    use StockIndicators, StockAnalysis;

    protected $fillable = ['ticker_id'];
    protected $hidden = ['id', 'created_at', 'updated_at', 'ticker_id', 'data', 'projections', 'ticker'];
    protected $appends = ['symbol', 'value', 'nextDay', 'fiveDay', 'tenDay', 'quickLook'];

    public function ticker()
    {
        return $this->belongsTo(Ticker::class);
    }

    public function projections()
    {
        return $this->hasMany(StockProjection::class)->orderBy('id', 'DESC');
    }

    public function trainedModels()
    {
        return $this->hasMany(TrainedStockModel::class);
    }

    public function accuracy()
    {
        return $this->hasMany(ModelAccuracy::class)->orderBy('id', 'DESC');
    }

    public function portfolios()
    {
        return $this->belongsToMany(Portfolio::class);
    }

    public static function fetch($ticker) : self
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

    public function getProjectionsAttribute()
    {
        return $this->projections()->limit(3)->get();
    }

    public function getAccuracyAttribute()
    {
        return $this->accuracy()->limit(3)->get();
    }

    private function getDatasetFor(Collection $dataset, string $key, string $timePeriod)
    {
        return $dataset->filter(function ($projection) use ($key, $timePeriod) {
            return $projection->$key === $timePeriod;
        })->first();
    }

    private function getProjectionAndAccuracyFor(string $timePeriod)
    {
        $projection = $this->getDatasetFor($this->projections, 'projection_for', $timePeriod);
        $accuracy = $this->getDatasetFor($this->accuracy, 'time_period', $timePeriod);

        return ['projection' => $projection, 'accuracy' => $accuracy];
    }

    private function getVerdictFor(string $timePeriod)
    {
        $projectionAndAccuracy = $this->getProjectionAndAccuracyFor($timePeriod);
        $verdict = $projectionAndAccuracy['projection']['verdict'];
        $accuracy = $projectionAndAccuracy['accuracy']['accuracy_'.str_replace(' ', '_', $verdict)] * 100;

        return [
            'verdict'   => $verdict,
            'accuracy'  => $accuracy,
        ];
    }

    private function getProbabilityLikelyOutcomeFor(string $timePeriod)
    {
        $projection = $this->getDatasetFor($this->projections, 'projection_for', $timePeriod);
        $probabilityProfit = round($projection->probabilityProfit * 100);
        $probabilityLoss = round($projection->probabilityLoss * 100);

        if ($probabilityProfit > $probabilityLoss) {
            return ['profit' => $probabilityProfit];
        }
        return ['loss' => $probabilityLoss];
    }

    public function getQuickLookAttribute()
    {
        $lastUpdatedOn = $this->lastUpdate->created_at->format('d/m/Y - H:i');
        $nextDayBroad = $this->getProbabilityLikelyOutcomeFor('next day');
        $fiveDayBroad = $this->getProbabilityLikelyOutcomeFor('five day');
        $tenDayBroad = $this->getProbabilityLikelyOutcomeFor('ten day');

        return collect([
            'price'         => $this->value,
            'change'        => $this->lastUpdate->change,
            'changePercent' => $this->lastUpdate->change_percent,
            'lastUpdate'    => $lastUpdatedOn,
            'nextDay'       => $nextDayBroad,
            'fiveDay'       => $fiveDayBroad,
            'tenDay'        => $tenDayBroad,
        ]);
    }

    public function getNextDayAttribute()
    {
        return $this->getVerdictFor('next day');
    }

    public function getFiveDayAttribute()
    {
        return $this->getVerdictFor('five day');
    }

    public function getTenDayAttribute()
    {
        return $this->getVerdictFor('ten day');
    }

    public function getLastTrainedModel($profitWindow = 1)
    {
        $model = $this->trainedModels()->where('profit_window', $profitWindow)->get()->last();
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
