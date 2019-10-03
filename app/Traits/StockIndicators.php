<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Laratrade\Trader\Facades\Trader;

trait StockIndicators
{
    public function getOpenAttribute()
    {
        return $this->data->pluck('open')->values()->toArray();
    }

    public function getHighAttribute()
    {
        return $this->data->pluck('high')->values()->toArray();
    }

    public function getLowAttribute()
    {
        return $this->data->pluck('low')->values()->toArray();
    }

    public function getCloseAttribute()
    {
        return $this->data->pluck('close')->values()->toArray();
    }

    public function getRealAttribute()
    {
        return $this->close;
    }

    // ----===== Stock analysis methods =====----

    public function getSmaAttribute()
    {
        return $this->sma();
    }

    public function sma($timePeriod = 30)
    {
        return Trader::sma($this->real, $timePeriod);
    }

    public function getEmaAttribute()
    {
        return $this->ema();
    }

    public function ema($timePeriod = 30)
    {
        return Trader::ema($this->real, $timePeriod);
    }

    public function getWmaAttribute()
    {
        return $this->wma();
    }

    public function wma($timePeriod = 30)
    {
        return Trader::wma($this->real, $timePeriod);
    }

    public function getRsiAttribute()
    {
        return $this->rsi();
    }

    public function rsi($timePeriod = 14)
    {
        return Trader::rsi($this->real, $timePeriod);
    }

    public function getUltoscAttribute()
    {
        return $this->ultosc();
    }

    public function ultosc($tpd1 = 7, $tpd2 = 14, $tpd3 = 28)
    {
        return Trader::ultosc($this->high, $this->low, $this->close, $tpd1, $tpd2, $tpd3);
    }

    public function getTypPriceAttribute()
    {
        return $this->typPrice();
    }

    public function typPrice()
    {
        return Trader::typprice($this->high, $this->low, $this->close);
    }

    public function getMidPriceAttribute()
    {
        return $this->midPrice();
    }

    public function midPrice($timePeriod = 14)
    {
        return Trader::midprice($this->high, $this->low, $timePeriod);
    }

    public function getDxAttribute()
    {
        return $this->dx();
    }

    public function dx($timePeriod = 14)
    {
        return Trader::dx($this->high, $this->low, $this->close, $timePeriod);
    }

    public function getSarAttribute()
    {
        return $this->sar();
    }

    public function sar($acceleration = 0.02, $maximum = 0.2)
    {
        return Trader::sar($this->high, $this->low, $acceleration, $maximum);
    }

    public function trendIndicators()
    {
        $data = [];
        $dx = collect($this->dx());
        $rsi = collect($this->rsi());
        $sar = collect($this->sar());
        $close = $this->close;
        $sarDelta = $sar->map(function ($sar, $index) use ($close) {
            return $close[$index] - $sar;
        });

        foreach (range(0, count($close) - 1) as $index) {
            $data[$index] = [
                'dx'   => $dx->get($index),
                'rsi'  => $rsi->get($index),
                'sard' => $sarDelta->get($index),
            ];
        }

        return collect($data)->filter(function ($row) {
            return !($row['dx'] == null || $row['rsi'] == null || $row['sard'] == null);
        });
    }

    private function nDayHistoricalProfitability($nDays)
    {
        $close = $this->close;
        return collect($close)->map(function ($currentClose, $index) use ($close, $nDays) {
            $hasNext = $index < (count($close) - $nDays);
            if ($hasNext == false) {
                return null;
            }
            return $close[$index + $nDays] > $currentClose;
        });
    }

    public function nextDayHistoricalProfitability()
    {
        return $this->nDayHistoricalProfitability(1);
    }

    public function fiveDayHistoricalProfitability()
    {
        return $this->nDayHistoricalProfitability(5);
    }

    public function tenDayHistoricalProfitability()
    {
        return $this->nDayHistoricalProfitability(10);
    }

    public function formatProfitabilityAndIndicators(Collection $profitability)
    {
        // Get the total number of points to go through
        $numberPoints = count($profitability);

        // Snag the indicators for the time period, pad the beginning because
        // some of them have offset starting points (rsi starts at 14)
        $indicators = $this->trendIndicators();
        $paddedIndicators = $indicators->pad(-$numberPoints, null);

        // Loop through all of the points and filter out days that have a null
        // value for either the indicator or the profit projection.
        // This is done to ensure the profit projections and indicators begin
        // and end at the same index.
        foreach (range(0, $numberPoints - 1) as $index) {
            $profitProjection = $profitability->get($index);
            $pointIndicators = $paddedIndicators->get($index);
            if ($profitProjection === null || $pointIndicators === null) {
                $profitability->forget($index);
                $paddedIndicators->forget($index);
            }
        }

        return [
            'profitability' => $profitability,
            'indicators' => $paddedIndicators,
        ];
    }

}
