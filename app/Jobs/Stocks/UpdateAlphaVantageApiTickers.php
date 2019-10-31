<?php

namespace App\Jobs\Stocks;

use App\AlphaVantageApi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateAlphaVantageApiTickers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        AlphaVantageApi::all()->each(function (AlphaVantageApi $api) {
            $api->updateMostOutdatedTicker();
        });
    }
}
