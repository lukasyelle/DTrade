<?php

namespace App\Jobs\Robinhood\Tasks;

use App\Jobs\BaseTask;
use App\User;
use Laravel\Dusk\Browser;

class LogoutTask extends BaseTask
{
    /**
     * @param Browser $browser
     * @param User $user
     * @throws \Throwable
     */
    public function execute(Browser $browser, User $user = null)
    {
        $currentUrl = $browser->driver->getCurrentURL();
        if ($currentUrl != 'https://robinhood.com/') $browser->visit('https://robinhood.com/');

        $browser->waitForText('Account',10)
            ->click("a[href='/account']")
            ->waitForText('Portfolio Value', 10)
            ->assertSeeIn("a[href='/login']", 'Log Out')
            ->click("a[href='/login']");
    }
}