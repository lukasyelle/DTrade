<?php

namespace App\Jobs\Robinhood\Tasks;


use App\Jobs\BaseTask;
use App\User;
use Exception;
use Laravel\Dusk\Browser;

class StockSearchTask extends BaseTask
{
    /**
     * @param User|null $user
     * @throws \Throwable
     */
    public function execute(User $user = null)
    {
        $symbol = $this->params;
        if ($symbol == null) throw new Exception('Ticker symbol must be sent as the parameter to the task upon initialization.');

        $this->browse(function(Browser $browser) use ($symbol) {
            $browser->visit("https://robinhood.com/stocks/$symbol")
                ->waitForText(strtoupper($symbol),10);
        });
    }
}