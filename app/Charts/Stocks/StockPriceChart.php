<?php

namespace App\Charts\Stocks;

class StockPriceChart extends StockChart
{
    protected function setup()
    {
        $this->addLimitedDateLabels();
        $this->limitedDataset('Price', 'line', array_values($this->stock->close));
        $this->limitedDataset('SAR', 'scatter', array_values($this->stock->sar))->options([
            'symbolSize' => 3,
        ]);
        $this->limitedDataset('EMA', 'line', array_values($this->stock->ema));
        $this->limitedDataset('WMA', 'line', array_values($this->stock->wma));
    }
}
