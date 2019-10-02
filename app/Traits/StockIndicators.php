<?php

namespace App\Traits;

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
}
