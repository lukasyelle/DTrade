<?php

namespace App\Console;

use App\Jobs\Stocks\AnalyzeStock;
use App\Jobs\Stocks\UpdateTickerData;
use App\Stock;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
            $symbols = Stock::all()->pluck('symbol')->toArray();
            UpdateTickerData::dispatch($symbols);
        })->dailyAt('16:00');

        $schedule->call(function () {
            $symbols = Stock::all()->pluck('symbol')->toArray();
            AnalyzeStock::dispatch($symbols);
        })->dailyAt('16:10');
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
