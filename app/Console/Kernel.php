<?php

namespace App\Console;

use App\Jobs\Stocks\AnalyzeStock;
use App\Jobs\Stocks\CheckAccuracy;
use App\Jobs\Stocks\UpdateTickerData;
use App\Stock;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    public static function updateAndAnalyzeStocks()
    {
        $symbols = Stock::all()->pluck('symbol');
        $symbols->each(function ($symbol) {
            UpdateTickerData::withChain([
                new AnalyzeStock($symbol),
                new CheckAccuracy($symbol),
            ])->dispatch($symbol);
        });
        $symbolsString = '`'.implode('`, ', $symbols->toArray()).'``.';
        Log::debug("Launched chained jobs to update, analyze, and check prediction accuracy for $symbolsString");
    }

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            self::updateAndAnalyzeStocks();
        })->weekdays()->twiceDaily(12, 16);

        $schedule->call(function () {
            Artisan::call('horizon:snapshot');
            Log::debug('horizon:snapshot');
        })->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
