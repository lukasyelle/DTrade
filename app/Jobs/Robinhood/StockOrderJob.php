<?php

namespace App\Jobs\Robinhood;

use App\Jobs\BrowserJob;
use App\User;

class StockOrderJob extends BrowserJob
{
    protected $orderDetails;

    public function __construct($orderDetails, User $user = null, array $tags = [])
    {
        parent::__construct($user, $tags);
        $this->orderDetails = $orderDetails;
    }

    /**
     * @throws \Exception
     */
    public function setup()
    {
        $this->debug = true;
        $this->addTasks([
            new Tasks\LoginTask(),
            new Tasks\StockOrderTask($this->orderDetails),
            new Tasks\LogoutTask(),
        ]);
    }
}
