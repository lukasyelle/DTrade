<?php

namespace App\Jobs\Robinhood\Tasks;

use App\Jobs\BaseTask;
use App\User;
use Exception;
use Laravel\Dusk\Browser;

class LogoutTask extends BaseTask
{
    /**
     * @param User $user
     * @throws \Throwable
     */
    public function execute(User $user = null)
    {
        $this->browse(function(Browser $browser) {
            $browser->visit('https://robinhood.com/')
                ->waitForText('Account',10)
                ->click("a[href='/account']")
                ->waitForText('Portfolio Value', 10)
                ->assertSeeIn("a[href='/login']", 'Log Out')
                ->click("a[href='/login']");
        });
    }
}