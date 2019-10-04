<?php

namespace App\Jobs\Stocks;

use App\Ticker;
use Illuminate\Support\Facades\Artisan;

class UpdateTickerData extends StockJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->symbols as $symbol) {
            $ticker = Ticker::fetch($symbol);
            if ($ticker instanceof Ticker) {
                $ticker->updateData();
            }
        }
    }
}
