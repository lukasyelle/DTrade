<?php

namespace App\Charts\Stocks;

use Carbon\Carbon;

class StockPriceChart extends StockChart
{
    protected function setup()
    {
        $dataPoints = $this->stock->data;
        $this->labels($dataPoints->pluck('created_at')->map(function (Carbon $date) {
            return $date->toDateString();
        }));
        $this->options(['symbolSize' => 1]);
        $this->dataset('Price', 'line', array_values($this->stock->close));
        $this->dataset('SAR', 'scatter', array_values($this->stock->sar))->options([
            'symbolSize' => 3,
        ]);
        $this->dataset('EMA', 'line', array_pad(array_values($this->stock->ema), -365, 0));
        $this->dataset('WMA', 'line', array_pad(array_values($this->stock->wma), -365, 0));
    }
}
