<?php

namespace App\Jobs\Robinhood;

use App\Jobs\BrowserJob;

class JobTest extends BrowserJob
{
    /**
     * @throws \Exception
     */
    public function setup()
    {
        $orderDetails = [
            'order'         => 'buy',
            'order_type'    => 'stop limit',
            'ticker'        => 'dse',
            'shares'        => '1',
            'limit_price'   => '0.5',
            'stop_price'    => '0.45',
            'expiration'    => 'Good till Canceled',
        ];

        $this->debug = true;
        $this->addTasks([
            new Tasks\LoginTask(),
            new Tasks\StockOrderTask($orderDetails),
            new Tasks\LogoutTask(),
        ]);
    }
}
