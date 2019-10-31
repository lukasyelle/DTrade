<?php

namespace App\Jobs\Stocks;

use App\Stock;
use App\TickerData;

class MarkEndOfDayData extends StockJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $stock = Stock::fetch($this->symbol);
        $lastDataPoint = $stock->data->last();
        if ($lastDataPoint instanceof TickerData && $lastDataPoint->is_intraday) {
            $lastDataPoint->is_intraday = false;
            $lastDataPoint->save();
        }
    }
}
