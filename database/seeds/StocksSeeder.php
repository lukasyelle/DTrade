<?php

use App\Jobs\DownloadTickerHistory;
use Illuminate\Database\Seeder;

class StocksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $desiredTickers = [
            'aapl',
            'amd',
            'clf',
            'wti',
            'hmy',
            'f',
            'gpro',
            'dnr',
            'pbr.a',
            'ge',
        ];
        DownloadTickerHistory::dispatch($desiredTickers);
    }
}
