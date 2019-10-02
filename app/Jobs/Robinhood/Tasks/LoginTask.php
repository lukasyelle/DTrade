<?php

namespace App\Jobs\Robinhood\Tasks;

use App\Jobs\BrowserTask;
use App\User;
use Carbon\Carbon;
use Exception;
use Laravel\Dusk\Browser;

class LoginTask extends BrowserTask
{
    /**
     * @param User    $user
     * @param Browser $browser
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     * @throws Exception                                      if the user does not have a Robinhood account
     */
    public function execute(User $user = null, Browser $browser = null)
    {
        if ($browser == null) {
            throw new Exception('User not passed to login task');
        }
        $robinhoodAccount = $user->platforms()->where('platform', 'robinhood')->first();
        if ($robinhoodAccount == null) {
            throw new Exception('User does not have a linked Robinhood account');
        }
        $browser->visit('https://robinhood.com/login')
                ->type("input[name='username']", $robinhoodAccount->username)
                ->type("input[name='password']", decrypt($robinhoodAccount->password))
                ->click("button[type='submit']")
                ->waitForText('Home');

        $robinhoodAccount->last_login = Carbon::now()->toDateTimeString();
        $robinhoodAccount->save();
    }
}
