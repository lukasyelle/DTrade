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
//        $dataPoints = $this->stock->data;
//        $indicatorsWithClosing = $this->stock->trendIndicators()->map(function ($indicatorSet, $index) use ($dataPoints) {
//            return array_merge([
//                'date' => $dataPoints->pluck('created_at')->get($index),
//                'price'=> $dataPoints->pluck('close')->get($index)
//            ], $indicatorSet);
//        });
//
//        $this->labels($indicatorsWithClosing->pluck('date')->map(function (Carbon $date) {
//            return $date->toDateString();
//        }));
//
//        $this->dataset('Price','line', $dataPoints->pluck('close'));
//        $this->dataset('RSI','line', $indicatorsWithClosing->pluck('rsi'));
//        $this->dataset('DX','line', $indicatorsWithClosing->pluck('dx'));
//        $projections = Stock::inRandomOrder()->first()->projections()->limit(3);

        $data = collect(['Next Day'=>[], 'Five Day'=>[], 'Ten Day'=>[]])->map(function ($item, $key) {
            $projection = $this->stock->projections()->limit(3)->where('projection_for', strtolower($key))->get();
            \Log::debug($projection);

            return [
                'name'  => $key,
                'value' => [
                    floatval($projection->pluck('probability_small_profit')->first()),
                    floatval($projection->pluck('probability_moderate_profit')->first()),
                    floatval($projection->pluck('probability_large_profit')->first()),
                    floatval($projection->pluck('probability_small_loss')->first()),
                    floatval($projection->pluck('probability_moderate_loss')->first()),
                    floatval($projection->pluck('probability_large_loss')->first()),
                ],
            ];
        });

        $max = $data->flatten()->max();

        $options = [
            'tooltip' => ['trigger' => 'axis'],
            'legend'  => [
                'x'    => 'center',
                'data' => ['Next Day', 'Five Day', 'Ten Day'],
            ],
            'xAxis' => [
                'show' => false,
            ],
            'yAxis' => [
                'show' => false,
            ],
            'radar' => [
                [
                    'indicator' => [
                        ['text'=> 'Small Profit', 'max' => $max + .1],
                        ['text'=> 'Moderate Profit', 'max' => $max + .1],
                        ['text'=> 'Large Profit', 'max' => $max + .1],
                        ['text'=> 'Small Loss', 'max' => $max + .1],
                        ['text'=> 'Moderate Loss', 'max' => $max + .1],
                        ['text'=> 'Large Loss', 'max' => $max + .1],
                    ],
                    'center' => ['50%', '50%'],
                    'radius' => 150,
                ],
            ],
            'series' => [
                [
                    'type'      => 'radar',
                    'tooltip'   => ['trigger' => 'item'],
                    'itemStyle' => ['normal' => ['areaStyle' => ['type' => 'default']]],
                    'data'      => $data->values()->toArray(),
                ],
            ],
        ];

        $this->options($options, false);
        $this->dataset('empty', 'radar', []);
    }
}
