<?php

namespace App\Charts\Stocks;

use Carbon\Carbon;

class StockIndicators extends StockChart
{
    public function setup()
    {
        $this->dataset('RSI','line', array_values($this->stock->rsi));
        $this->dataset('DX','line', array_values($this->stock->dx));
        $this->dataset('Ultra Oscillator','line', array_values($this->stock->ultosc));
    }
}
