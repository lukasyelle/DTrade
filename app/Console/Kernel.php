<?php

namespace App\Console;

use App\Jobs\Stocks\AnalyzeStock;
use App\Jobs\Stocks\CheckAccuracy;
use App\Jobs\Stocks\MarkEndOfDayData;
use App\Jobs\Stocks\UpdateAlphaVantageApiTickers;
use App\Stock;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;
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

    // How long to wait between updating the most outdated stocks.
    public static $updateInterval = 12;

    public static function analyzeStocks()
    {
        $symbols = Stock::all()->pluck('symbol');
        $symbols->each(function ($symbol) {
            AnalyzeStock::dispatch($symbol);
            CheckAccuracy::dispatch($symbol);
        });
        $symbolsString = '`'.implode('`, ', $symbols->toArray()).'``.';
        Log::debug("Launched chained jobs to analyze, and check prediction accuracy for $symbolsString");
    }

    public static function sleepFor($numberSeconds, Carbon &$now = null)
    {
        $now = ($now === null) ? Carbon::now() : $now;
        time_sleep_until($now->addSeconds($numberSeconds)->timestamp);
    }

    public static function keepTickersUpdated(Carbon &$now = null)
    {
        $now = ($now === null) ? Carbon::now() : $now;

        for ($i = 0; $i < (60 / self::$updateInterval); $i++) {
            UpdateAlphaVantageApiTickers::dispatch();
            self::sleepFor(self::$updateInterval, $now);
        }
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
        // Keep stock data updated during trading hours.
        $schedule->call(function () {
            self::keepTickersUpdated();
        })->everyMinute()->weekdays()->between('09:30', '16:00');

        // At the end of the trading day mark the last intraday update as the
        // EOD data point. Dispatch a minute after market close to allow pending
        // updates to finish.
        $schedule->call(function () {
            $stocks = Stock::all()->pluck('symbol')->toArray();
            MarkEndOfDayData::dispatch($stocks);
        })->dailyAt('16:01');

        // After the last data point for each stock has been designated the EOD
        // quote, reanalyze projections for the next day.
        $schedule->call(function () {
            self::analyzeStocks();
        })->dailyAt('16:02');

        // Snapshot horizon logs for metric tracking every hour
        $schedule->call(function () {
            Artisan::call('horizon:snapshot');
            Log::debug('horizon:snapshot');
        })->hourly();
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
