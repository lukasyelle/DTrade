<?php

namespace App\Jobs;

use App\Ticker;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Artisan;

class DownloadTickerHistory implements ShouldQueue
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
        $this->symbols = is_array($symbols) ? $symbols : [$symbols];
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->symbols as $symbol) {
            $tickerExisted = Ticker::symbolExists($symbol);
            $ticker = Ticker::fetch($symbol);
            if ($ticker instanceof Ticker && $tickerExisted) {
                // If the ticker didnt exist, it will download the history on
                // creation (which will happen in the `fetch` method above).
                $ticker->downloadInitialHistory();
            }
        }
    }
}
