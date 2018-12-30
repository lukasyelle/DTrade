<?php

namespace App\Jobs\Robinhood;

use App\Jobs\BrowserJob;
use App\Jobs\Robinhood\Tasks\LoginTask;
use App\User;


class JobTest extends BrowserJob
{
    public function __construct(User $user = null, array $tags = [])
    {
        \Log::debug($user);

        $this->tasks = [new LoginTask()];

        parent::__construct($user, $tags);
    }
}
