<?php

namespace App\Jobs\Stocks;

use App\ModelAccuracy;
use App\Stock;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CheckAccuracy extends StockJob
{
    private function runTrial(Stock $stock, int $numDays)
    {
        $now = Carbon::now();
        $results = collect($stock->testAccuracy($numDays));
        $finish = Carbon::now();
        $duration = $now->diffInSeconds($finish);

        return $results->put('duration', $duration);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $stock = Stock::fetch($this->symbol);
        $tests = collect([
            'next day'  => $this->runTrial($stock, 1),
            'five day'  => $this->runTrial($stock, 5),
            'ten day'   => $this->runTrial($stock, 10),
        ]);
        $tests->each(function (Collection $accuracyData, $timePeriod) use ($stock) {
            $accuracyModel = [
                'stock_id'      => $stock->id,
                'time_period'   => $timePeriod,
            ];
            $accuracyData->each(function ($item, $key) use (&$accuracyModel) {
                $key = ($key == 'duration') ? $key : 'accuracy_'.str_replace(' ', '_', $key);
                $accuracyModel[$key] = $item;
            });
            ModelAccuracy::create($accuracyModel);
        });
    }
}
