<?php

namespace App\Console\Commands;

use App\Console\Kernel;
use App\Jobs\Stocks\AnalyzeStock as AnalyzeStockJob;
use App\Jobs\Stocks\CheckAccuracy as CheckAccuracyJob;
use App\Jobs\Stocks\MarkEndOfDayData;
use App\Jobs\Stocks\UpdateTickerData as UpdateTickerDataJob;
use App\Stock;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class UpdateStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:update {?symbol} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to update the data for a particular stock ticker.';


    /**
     * A collection of all stocks to update.
     *
     * @var Collection
     */
    protected $stocks;

    /**
     * An array of all stocks symbols to keep track of.
     *
     * @var Collection
     */
    protected $symbols;


    private function updateAllStocks()
    {
        $this->alert('Updating all stocks... This may take a while.');
        $numberLoops = ceil($this->stocks->count() * Kernel::$updateInterval / 60);
        for ($i = 0; $i < $numberLoops; $i++) {
            Kernel::keepTickersUpdated();
            Kernel::sleepFor(60);
        }
        Kernel::sleepFor(Kernel::$updateInterval);
        $this->alert('Finished updating all stocks.');
    }

    private function markEndOfDay()
    {
        $this->alert('Marking the latest datapoint as EOD for all stocks.');
        MarkEndOfDayData::dispatch($this->symbols);
        Kernel::sleepFor(5);
        $this->alert('Done marking EOD datapoints.');
    }

    private function analyzeAllStocks()
    {
        $this->alert('Analyzing all stocks.');
        AnalyzeStockJob::dispatch($this->symbols);
        Kernel::sleepFor($this->stocks->count() * 5);
        $this->alert('Done analyzing all stocks.');
    }

    private function checkAllAccuracyMeasurements()
    {
        $this->alert('Dispatching jobs to verify accuracy of models.');
        CheckAccuracyJob::dispatch($this->symbols);
        Kernel::sleepFor(10);
        $this->alert('Jobs dispatched. They will take a while to complete.');
    }


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->option('all')) {

            $this->stocks = Stock::all();
            $this->symbols = $this->stocks->pluck('symbol')->toArray();

            $this->updateAllStocks();
            $this->markEndOfDay();
            $this->analyzeAllStocks();
            $this->checkAllAccuracyMeasurements();

        } else {

            $symbol = strtoupper($this->argument('symbol'));
            UpdateTickerDataJob::dispatch($symbol);
            $this->info("Job dispatched to pull the latest market data for `$symbol`.");

        }
    }
}
