<?php

namespace App\Jobs\Stocks;

use App\Ticker;

class DownloadTickerHistory extends StockJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->symbols as $symbol) {
            $tickerExisted = Ticker::symbolExists($symbol);
            $ticker = Ticker::fetch($symbol);
            if ($ticker instanceof Ticker && $tickerExisted) {
                // If the ticker didnt exist, it will download the history on
                // creation (which will happen in the `fetch` method above).
                $ticker->downloadInitialHistory();
            }
        }
    }
}
