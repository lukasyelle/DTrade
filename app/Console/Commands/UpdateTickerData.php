<?php

namespace App\Console\Commands;

use App\Ticker;
use Illuminate\Console\Command;

class UpdateTickerData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:ticker {ticker}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to update the data for a particular stock ticker';

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
        $symbol = strtoupper($this->argument('ticker'));
        $ticker = Ticker::fetch($symbol);
        if ($ticker instanceof Ticker) {
            $ticker->updateData();
        }

        return $ticker;
    }
}
