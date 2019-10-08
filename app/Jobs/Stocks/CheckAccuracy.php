<?php

namespace App\Jobs;

use App\Jobs\Stocks\StockJob;
use App\Stock;

class CheckAccuracy extends StockJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $stock = Stock::fetch($this->symbol);
        $tests = collect([
            'next day'  => collect($stock->testAccuracy()),
            'five day'  => collect($stock->testAccuracy()),
            'ten day'   => collect($stock->testAccuracy()),
        ]);
    }
}
