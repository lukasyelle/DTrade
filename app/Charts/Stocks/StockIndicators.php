<?php

namespace App\Charts\Stocks;

class StockIndicators extends StockChart
{
    public function setup()
    {
        $this->addLimitedDateLabels();
        $this->limitedDataset('RSI', 'line', array_values($this->stock->rsi));
        $this->limitedDataset('DX', 'line', array_values($this->stock->dx));
        $this->limitedDataset('Ultimate Oscillator', 'line', array_values($this->stock->ultosc));
    }
}
