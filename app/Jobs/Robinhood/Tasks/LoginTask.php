<?php

namespace App\Jobs\Robinhood\Tasks;

use App\Jobs\BaseTask;
use App\User;
use Exception;
use Laravel\Dusk\Browser;

class LoginTask extends BaseTask
{
    /**
     * @param User $user
     * @param null $params
     * @throws \Throwable
     */
    public function execute(User $user = null, $params = null)
    {
        if ($user == null) throw new Exception('User not passed to login task');

        $robinhoodLogin = $user->platforms()->where('platform', 'robinhood')->first();

        if ($robinhoodLogin == null) throw new Exception('User does not have a linked Robinhood account');

        $this->browse(function(Browser $browser) use ($robinhoodLogin) {

            $browser->visit('https://robinhood.com/login')
                    ->type("input[name='username']", $robinhoodLogin->username)
                    ->type("input[name='password']", decrypt($robinhoodLogin->password))
                    ->click("button[type='submit']");

        });
    }
}