<?php

namespace App\Charts\Stocks;

use App\Stock;
use ConsoleTVs\Charts\Classes\Echarts\Chart;

abstract class StockChart extends Chart
{
    protected $stock;

    public function __construct(Stock $stock)
    {
        parent::__construct();

        $this->title($stock->symbol);
        $this->stock = $stock;
        $this->setup();
    }

    abstract protected function setup();
}
