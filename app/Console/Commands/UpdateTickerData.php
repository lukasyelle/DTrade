<?php

namespace App\Console\Commands;

use App\Jobs\Stocks\UpdateTickerData as UpdateTickerDataJob;
use Illuminate\Console\Command;

class UpdateTickerData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:update {symbol}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to update the data for a particular stock ticker.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $symbol = strtoupper($this->argument('symbol'));
        UpdateTickerDataJob::dispatch($symbol);
        $this->info("Job dispatched to pull the latest market data for `$symbol`.");
    }
}
