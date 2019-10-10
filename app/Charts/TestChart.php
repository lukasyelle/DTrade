<?php

namespace App\Charts;

use App\Stock;
use Carbon\Carbon;
use ConsoleTVs\Charts\Classes\Echarts\Chart;

class TestChart extends Chart
{
    private $stock;

    /**
     * Initializes the chart.
     *
     * @param Stock $stock
     *
     * @return void
     */
    public function __construct(Stock $stock)
    {
        parent::__construct();

        $this->title($stock->symbol);
        $this->stock = $stock;
        $this->setup();
    }

    private function setup()
    {
        $dataPoints = $this->stock->data;
        $indicatorsWithClosing = $this->stock->trendIndicators()->map(function ($indicatorSet, $index) use ($dataPoints) {
            return array_merge([
                'date' => $dataPoints->pluck('created_at')->get($index),
                'price'=> $dataPoints->pluck('close')->get($index)
            ], $indicatorSet);
        });

        $this->labels($indicatorsWithClosing->pluck('date')->map(function (Carbon $date) {
            return $date->toDateString();
        }));

        $this->dataset('Price','line', $dataPoints->pluck('close'));
        $this->dataset('RSI','line', $indicatorsWithClosing->pluck('rsi'));
        $this->dataset('DX','line', $indicatorsWithClosing->pluck('dx'));
    }
}
