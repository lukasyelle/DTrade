<?php

namespace App\Jobs\Robinhood\Tasks;

use App\Jobs\BrowserTask;
use App\User;
use Laravel\Dusk\Browser;

class StockOrderTask extends BrowserTask
{
    public function setup()
    {
        $this->requiredParams = ['order', 'order_type', 'ticker', 'shares'];
    }

    /**
     * @param Browser $browser
     * @param User    $user
     * @param $order
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    private function submitOrder(Browser $browser, User $user, $order)
    {
        $browser->press('Review Order')
                ->waitForText('You are')
                ->press("Submit $order");

        // Handle the cases where robinhood requires you reenter your password.
        if ($browser->element("input[name='password']")) {
            $robinhoodLogin = $user->platforms()->where('platform', 'robinhood')->first();
            $browser->type("input[name='password']", decrypt($robinhoodLogin->password))->press('Continue');
        }
    }

    /**
     * @param User    $user
     * @param Browser $browser
     *
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
    public function execute(User $user = null, Browser $browser = null)
    {
        // Go to the stock page using the Stock Search Task
        $ticker = strtoupper($this->params['ticker']);
        (new StockSearchTask($ticker))->execute($browser, $browser);

        $order = ucfirst($this->params['order']);
        $orderType = $this->params['order_type'];

        $browser->assertUrlIs("https://robinhood.com/stocks/$ticker")
                ->waitForText($ticker);

        $orderLink = $order.' '.$ticker;
        if ($browser->seeLink($orderLink)) {
            $browser->clickLink($orderLink); // Click the appropriate order button
        }

        $browser->type("input[name='quantity']", $this->params['shares']);  // Type the quantity for the order

        if ($orderType != 'market') {
            // Non-market orders have more fields than regular orders, make sure they are set in the params
            $this->addRequiredParams(['price', 'expiration']);

            if ($orderType == 'stop limit') {
                // Stop limit orders require the stop price to be sent too
                $this->addRequiredParams('stop_price');
                $browser->type("input[name='stop_price']", $this->params['stop_price']);
            }

            // Click the dot menu and select the appropriate order. Then enter the necessary information
            $browser->click('._1sTI87VcHv4IA7ntSiCczw') // Click the dot menu
                    ->waitForText('Order Type') // Wait for the dialog to appear
                    ->clickLink(ucwords("$orderType Order")) // Click on the correct order
                    ->pause(1000) // Wait for the form to adjust
                    ->clear("input[name='price']")
                    ->type("input[name='price']", $this->params['price']) // Type the base trade price
                    ->click('.Select-value') // Select the right expiration option for the order
                    ->clickLink($this->params['expiration'], 'div') // It is very possible this will not work.. Just saying.
                    ->waitForText($this->params['expiration'])
                    ->pause(25000);
        }

        // Done filling out the order, try to submit it!
//            $this->submitOrder($browser, $user, $order);
    }
}
