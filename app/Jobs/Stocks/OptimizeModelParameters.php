<?php

namespace App\Jobs\Stocks;

use App\ModelParameter;
use App\Stock;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OptimizeModelParameters extends StockJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $stock = Stock::fetch($this->symbol);
        if ($stock instanceof Stock) {
            if ($stock->modelParameters instanceof ModelParameter) {
                $stock->modelParameters->optimize();
            }
        }
    }
}
