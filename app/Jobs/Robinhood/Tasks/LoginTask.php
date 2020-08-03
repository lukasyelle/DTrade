<?php

namespace App\Jobs\Robinhood\Tasks;

use App\Events\Robinhood\MultiFactorFailed;
use App\Events\Robinhood\MultiFactorNecessary;
use App\Jobs\BrowserTask;
use App\MFACode;
use App\User;
use Carbon\Carbon;
use Exception;
use Facebook\WebDriver\Exception\TimeOutException;
use Illuminate\Support\Facades\Log;
use Laravel\Dusk\Browser;

class LoginTask extends BrowserTask
{
    public $codeInputSelector = 'input[placeholder="000000"]';

    private function tryMfaCode(Browser $browser, $code)
    {
        Log::debug("Trying to type code: $code");
        try {
            $browser->type($this->codeInputSelector, $code)
                    ->press('Confirm')
                    ->waitForText('Account');

            return true;
        }
        catch (TimeOutException $exception) {
            return false;
        }
    }

    private function isPreviousOlder($current, $previous)
    {
        if ($current instanceof MFACode && $previous instanceof MFACode) {
            return $current->created_at > $previous->created_at;
        }

        return false;
    }

    private function handleMfa(User $user, Browser $browser)
    {
        $mfaAccepted = false;
        $mfaAttempted = false;
        $previousMfaCode = null;
        $loops = 0;

        while (!$mfaAccepted && $loops < 60) {
            // For three minutes, wait for the user to get a code.
            $loops += 1;
            $mfaCode = User::where('id', $user->id)->first()->mfaCode()->get()->first();
            $previousMfaCodeIsOlder = $this->isPreviousOlder($mfaCode, $previousMfaCode);

            if ($mfaCode && $mfaCode->created_at->diffInSeconds() < 10) {
                // MFA Code exists for a user, and it is relatively new
                if (!$mfaAttempted || $previousMfaCodeIsOlder) {
                    // Current MFA Has not been attempted, try it
                    $mfaAccepted = $this->tryMfaCode($browser, $mfaCode->code);
                    $mfaAttempted = true;

                    if (!$mfaAccepted) {
                        event(new MultiFactorNecessary($user , 'That code was invalid, please try again.'));
                    }
                }
            }

            $previousMfaCode = $mfaCode;
            sleep(2);
        }

        if (!$mfaAccepted) {
            event(new MultiFactorFailed($user));
            $browser->quit();
        }
    }

    private function clickTextOrEmailOption(Browser $browser)
    {
        try {
            $browser->assertSee('Text Me')
                    ->press('Text Me')
                    ->waitFor($this->codeInputSelector);

            Log::debug('Done waiting for code input box.');
        }
        catch (Exception $e) {
            $browser->press('Email Me')
                    ->waitFor($this->codeInputSelector);
        }
    }

    private function hasMfaRequirement(Browser $browser)
    {
        try {
            sleep(2);
            $browser->waitForText('Verify Your Identity');
            $this->clickTextOrEmailOption($browser);

            return true;
        }
        catch (TimeOutException $e) {
            return $browser->element($this->codeInputSelector) ? true : false;
        }
    }

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
                ->click("button[type='submit']");

        if ($this->hasMfaRequirement($browser)) {
            event(new MultiFactorNecessary($user , 'Please enter the MFA code you were just sent below.'));
            $this->handleMfa($user, $browser);
        }

        $browser->waitForText('Account');

        $robinhoodAccount->last_login = Carbon::now()->toDateTimeString();
        $robinhoodAccount->save();
    }
}
