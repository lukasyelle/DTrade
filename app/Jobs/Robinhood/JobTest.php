<?php

namespace App\Jobs\Robinhood;

use App\Jobs\BrowserJob;
use App\Jobs\Robinhood\Tasks\LoginTask;
use App\Jobs\Robinhood\Tasks\LogoutTask;
use App\Jobs\Robinhood\Tasks\StockSearchTask;
use App\User;


class JobTest extends BrowserJob
{
    public function setup()
    {
        $this->debug = true;
        $this->addTasks([new LoginTask(), new StockSearchTask('aapl'), new LogoutTask()]);
    }
}
