<?php

namespace App\Console\Commands;

use App\Jobs\Stocks\CheckAccuracy as CheckAccuracyJob;
use Illuminate\Console\Command;

class CheckAccuracy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:accuracy {symbol}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the accuracy of the ML model for the given stock symbol. Dispatches the `CheckAccuracy` job.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $symbol = $this->argument('symbol');
        CheckAccuracyJob::dispatch($symbol);
        $this->info("Launched job to check the accuracy of the SVM for `$symbol`");
    }
}
