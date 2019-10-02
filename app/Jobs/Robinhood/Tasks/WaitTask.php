<?php

namespace App\Jobs\Robinhood\Tasks;

use App\Jobs\BrowserTask;
use App\User;
use Laravel\Dusk\Browser;

class WaitTask extends BrowserTask
{
    public function setup()
    {
        $this->requiredParams = true;
    }

    /**
     * Requires the initialized parameter set and be sent as an integer.
     *
     * @param User    $user
     * @param Browser $browser
     */
    public function execute(User $user = null, Browser $browser = null)
    {
        $browser->pause($this->params);
    }
}
