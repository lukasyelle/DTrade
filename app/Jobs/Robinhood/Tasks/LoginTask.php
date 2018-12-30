<?php

namespace App\Jobs\Robinhood\Tasks;

use App\User;
use Exception;
use Laravel\Dusk\Browser;

class LoginTask extends BaseTask
{
    /**
     * @param User $user
     * @return bool|Exception
     * @throws \Throwable
     */
    public function execute(User $user = null)
    {
        if ($user == null) return new Exception('User not passed to login task');

        $this->browse(function(Browser $browser) use ($user) {

            $browser->visit('https://robinhood.com/login');

        });

        return true;
    }
}