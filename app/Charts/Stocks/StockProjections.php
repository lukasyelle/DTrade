<?php

namespace App\Charts\Stocks;

use Illuminate\Support\Collection;

class StockProjections extends StockChart
{
    public function setup()
    {
        $data = $this->formatData();

        $this->setOptions($data);

        // Set an empty dataset, as the graphing package I am using requires a
        // one to be set to determine the type of the chart, but doesnt support
        // rendering the 'radar' chart, so I have to set the data series in the
        // options of this chart.
        $this->dataset('empty', 'radar', []);
    }

    private function formatData()
    {
        return collect(['Next Day'=>[], 'Five Day'=>[], 'Ten Day'=>[]])->map(function ($item, $key) {
            $projection = $this->stock->projections()->limit(3)->where('projection_for', strtolower($key))->get();

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
    }

    private function setOptions(Collection $data)
    {
        $max = floatval($data->flatten()->max());
        $options = [
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
                    'center' => ['50%', '55%'],
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

        $this->displayAxes(false);
        $this->options($options, false);
    }
}
