<?php

namespace App\Jobs\Robinhood\Tasks;

use App\Jobs\BaseTask;
use App\User;
use Carbon\Carbon;
use Exception;
use Laravel\Dusk\Browser;

class LoginTask extends BaseTask
{
    /**
     * @param Browser $browser
     * @param User $user
     * @throws \Throwable
     */
    public function execute(Browser $browser, User $user = null)
    {
        if ($user == null) throw new Exception('User not passed to login task');

        $robinhoodAccount = $user->platforms()->where('platform', 'robinhood')->first();
        if ($robinhoodAccount == null) throw new Exception('User does not have a linked Robinhood account');

        $browser->visit('https://robinhood.com/login')
                ->type("input[name='username']", $robinhoodAccount->username)
                ->type("input[name='password']", decrypt($robinhoodAccount->password))
                ->click("button[type='submit']")
                ->waitForText('Home');

        $browser->waitFor('._2YApulnV3lazBStOvoKx6m');
        $portfolioValue = $browser->text("._2YApulnV3lazBStOvoKx6m");

        $robinhoodAccount->last_login = Carbon::now()->toDateTimeString();
        $robinhoodAccount->portfolio_value = str_replace('$', '', $portfolioValue);
        $robinhoodAccount->save();

    }
}