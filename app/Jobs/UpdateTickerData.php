<?php

namespace App\Jobs;

use App\Ticker;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class UpdateTickerData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $tickers;

    /**
     * Create a new job instance.
     *
     * @param null|string|array $tickers - the ticker(s) to update.
     *
     * @throws Exception if ticker is not passed to job.
     */
    public function __construct($tickers = null)
    {
        if ($tickers == null) {
            throw new Exception('Pass at least one ticker to update job');
        }
        $this->tickers = is_array($tickers) ? $tickers : [$tickers];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->tickers as $symbol) {
            Artisan::call("update:ticker $symbol");
        }
    }
}
