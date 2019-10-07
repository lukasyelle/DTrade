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
        $tickerExisted = Ticker::symbolExists($this->symbol);
        $ticker = Ticker::fetch($this->symbol);
        if ($ticker instanceof Ticker && $tickerExisted) {
            // If the ticker didnt exist, it will download the history on
            // creation (which will happen in the `fetch` method above).
            $ticker->downloadInitialHistory();
        }
    }
}
