<?php

namespace App\Console\Commands;

use App\Jobs\Stocks\DownloadTickerHistory as DownloadTickerHistoryJob;
use App\Ticker;
use Illuminate\Console\Command;

class DownloadTickerHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:history {symbol} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command dispatches the downloadTickerHistory job for a specific symbol';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $symbol = $this->argument('symbol');
        if (Ticker::symbolExists($symbol)) {
            // Download the history of a preexisting symbol only if it doesnt have its history yet
            $ticker = Ticker::fetch($symbol);
            if ($ticker->data->count() <= 10 || $this->option('force')) {
                DownloadTickerHistoryJob::dispatch($symbol);
                $this->info('Dispatched job to download history data.');
            } else {
                $this->error('The ticker symbol you provided already has a lot of data.');
                $this->error('You may override this check with the --force option.     ');
            }
        } else {
            // Create a new ticker and download its data.
            Ticker::fetch($symbol);
            $this->info("Created new Ticker and Stock for `$symbol`");
            $this->info('Dispatched job to download history data.');
        }
    }
}
