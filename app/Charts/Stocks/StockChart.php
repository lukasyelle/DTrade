<?php

namespace App\Charts\Stocks;

use App\Stock;
use Carbon\Carbon;
use ConsoleTVs\Charts\Classes\Echarts\Chart;

abstract class StockChart extends Chart
{
    protected $stock;

    public function __construct(Stock $stock)
    {
        parent::__construct();

        $this->stock = $stock;
        $this->setup();
    }

    protected function limit(array $dataset, int $limit = 300)
    {
        return collect($dataset)->reverse()->take($limit)->reverse()->values()->toArray();
    }

    protected function limitedDataset(string $name, string $type, array $dataset, int $limit = 300)
    {
        $dataset = $this->limit($dataset, $limit);

        return $this->dataset($name, $type, $dataset);
    }

    public function addLimitedDateLabels()
    {
        $data = $this->stock->data(true)->get();
        $dates = $data->pluck('created_at')->map(function (Carbon $date) {
            return $date->toDateString();
        })->toArray();
        $this->labels($this->limit($dates));
    }

    abstract protected function setup();
}
