<?php

namespace App\Jobs\Stocks;

use App\Stock;
use App\StockProjection;

class AnalyzeStock extends StockJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $stock = Stock::fetch($this->symbol);
        if ($stock instanceof Stock) {
            $projections = collect([
                'next day'  => collect($stock->nextDayProjection()),
                'five day'  => collect($stock->fiveDayProjection()),
                'ten day'   => collect($stock->tenDayProjection()),
            ]);
            $projections->each(function ($projectionData, $projectionFor) use ($stock) {
                $stockProjection = [
                    'stock_id'          => $stock->id,
                    'projection_for'    => $projectionFor,
                ];
                $projectionData->each(function ($item, $key) use (&$stockProjection) {
                    $key = ($key == 'verdict') ? $key : 'probability_'.str_replace(' ', '_', $key);
                    $stockProjection[$key] = $item;
                });
                StockProjection::create($stockProjection);
            });
        }
    }
}
