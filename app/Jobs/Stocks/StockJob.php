<?php

namespace App\Jobs\Stocks;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

abstract class StockJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $symbols;

    /**
     * Create a new job instance.
     *
     * @param string|array $symbols - the ticker symbol(s) to update.
     */
    public function __construct($symbols)
    {
        $this->symbols = array_map('strtoupper', (is_array($symbols) ? $symbols : [$symbols]));
    }

}
