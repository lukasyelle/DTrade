<?php

namespace App\Jobs\Stocks;

use App\Ticker;

class UpdateTickerData extends StockJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ticker = Ticker::fetch($this->symbol);
        if ($ticker instanceof Ticker) {
            $ticker->updateData();
        }
    }
}
