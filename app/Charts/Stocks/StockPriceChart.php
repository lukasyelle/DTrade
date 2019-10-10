<?php

namespace App\Charts\Stocks;

use Carbon\Carbon;

class StockPriceChart extends StockChart
{
    protected function setup()
    {
        $dataPoints = $this->stock->data;
        $this->labels($dataPoints->pluck('created_at')->map(function(Carbon $date) {
            return $date->toDateString();
        }));
        $this->dataset('Closing Price','line', $dataPoints->pluck('close'));
    }
}
