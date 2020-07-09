<?php

namespace App\Charts\Stocks;

use Illuminate\Support\Collection;

class StockIndicators extends StockChart
{
    private $indicators;

    private function getDataset($dataset)
    {
        return $this->indicators->pluck($dataset)->values()->toArray();
    }

    public function setup()
    {
        $this->indicators = $this->stock->trendIndicators();

        $this->addLimitedDateLabels();
        $this->limitedDataset('RSI', 'line', $this->getDataset('rsi'));
        $this->limitedDataset('DX', 'line', $this->getDataset('dx'));
        $this->limitedDataset('Ultimate Oscillator', 'line', $this->getDataset('ultosc'));
        $this->limitedDataset('WMA Delta', 'line', $this->getDataset('wmad'));
        $this->limitedDataset('SAR Delta', 'line', $this->getDataset('sard'));
        $this->limitedDataset('TSF Delta', 'line', $this->getDataset('tsfd'));
    }
}
