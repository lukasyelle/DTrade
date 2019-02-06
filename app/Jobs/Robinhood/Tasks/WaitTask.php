<?php

namespace App\Jobs\Robinhood\Tasks;

use App\Jobs\BaseTask;
use App\User;
use Laravel\Dusk\Browser;

class WaitTask extends BaseTask
{

    public function setup()
    {
        $this->requiredParams = true;
    }

    /**
     * Requires the initialized parameter set and be sent as an integer
     * @param Browser $browser
     * @param User|null $user
     * @throws \Throwable
     */
    public function execute(Browser $browser, User $user = null)
    {
        $browser->pause($this->params);
    }
}