<?php

namespace App\Console\Commands;

use App\Jobs\Stocks\AnalyzeStock as AnalyzeStockJob;
use Illuminate\Console\Command;

class AnalyzeStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analyze:stock {symbol}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates projections for a stock for the next day, five days, and ten days.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $symbol = $this->argument('symbol');
        AnalyzeStockJob::dispatch($symbol);
        $this->info("Launched job to analyze `$symbol`");
    }
}
