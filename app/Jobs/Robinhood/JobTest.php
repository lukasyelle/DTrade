<?php

namespace App\Jobs\Robinhood;

use App\Jobs\BrowserJob;
use App\Jobs\Robinhood\Tasks;


class JobTest extends BrowserJob
{
    /**
     * @throws \Exception
     */
    public function setup()
    {
        $orderDetails = [
            'order'=>'buy',
            'order_type'=>'stop limit',
            'ticker'=>'wft',
            'shares'=>'1',
            'price'=>'0.45',
            'stop_price'=>'0.5',
            'expiration'=>'Good till Canceled'
        ];

        $this->debug = true;
        $this->addTasks([
            new Tasks\LoginTask(),
            new Tasks\LogoutTask()
        ]);
    }
}
