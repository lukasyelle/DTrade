<?php

namespace App\Jobs\Robinhood\Tasks;

use App\Traits\BrowserScaffold;
use App\User;

class BaseTask
{
    use BrowserScaffold;

    public function execute(User $user = null)
    {
        // Do the thing!
    }
}