<?php

namespace App\Jobs;

use App\Ticker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class UpdateTickerData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $symbols;

    /**
     * Create a new job instance.
     *
     * @param string|array $symbols - the ticker symbol(s) to update.
     */
    public function __construct($symbols)
    {
        $this->symbols = array_map('strtoupper', (is_array($symbols) ? $symbols : [$symbols]));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->symbols as $symbol) {
            Artisan::call("update:ticker $symbol");
        }
    }
}
