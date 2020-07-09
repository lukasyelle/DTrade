<?php

namespace App\Console\Commands;

use App\Stock;
use App\Ticker;
use Illuminate\Console\Command;

class TestEstimator extends Command
{
    public $estimator;
    public $stock;
    private $trials;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'estimator:test {estimator?} {stock?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the classifier or regressor found in the Price Estimator trait. The "estimator" parameter should be "price" or "profit". The "stock" parameter should be the symbol of a stock you want to test the model on.';

    private function standardizeEstimator($estimator)
    {
        return strtolower(trim($estimator));
    }

    private function validateEstimator($estimator)
    {
        return in_array($this->standardizeEstimator($estimator), ['price', 'profit']);
    }

    private function setupEstimator()
    {
        $estimator = $this->argument('estimator');
        if ($estimator) {
            if ($this->validateEstimator($estimator)) {
                $this->estimator = $this->standardizeEstimator($estimator);
            } else {
                $this->error('Estimator parameter must be either "price" or "profit".');
            }
        } else {
            $this->estimator = 'profit';
        }
    }

    private function setupStock()
    {
        $stock = $this->argument('stock');
        if ($stock) {
            if (Ticker::symbolExists($stock)) {
                $this->stock = Stock::fetch($stock);
            } else {
                $this->error('The given stock ticker is not present in our system.');
            }
        } else {
            $this->stock = Stock::first();
        }
    }

    private function runTrial()
    {
        $result = $this->stock->generateReport($this->estimator === 'price');
        if (is_array($result)) {
            if (array_key_exists('overall', $result)) {
                $result = $result['overall']['f1_score'];
            } else if (array_key_exists('mean_absolute_percentage_error', $result)) {
                $result = $result['mean_absolute_percentage_error'];
            }
        }
        $this->trials->push($result);

        return $result;
    }

    public function __construct()
    {
        parent::__construct();

        $this->trials = collect();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->setupEstimator();
        $this->setupStock();
        $this->info("Testing the $this->estimator estimator for " . $this->stock->symbol . '.');
        $resType = $this->estimator === 'profit' ? 'overall f1 score' : 'mean percentage error';

        while ($this->trials->count() < 10) {
            $trial = $this->trials->count() + 1;
            $result = $this->runTrial();
            $this->info("Trial $trial | $resType: $result");
        }

        $result = $this->trials->average();
        $this->alert("Average $resType: $result");
    }
}
