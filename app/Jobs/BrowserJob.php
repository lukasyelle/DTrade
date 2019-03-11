<?php

namespace App\Jobs;

use App\Traits\BrowserScaffold;
use App\User;
use Illuminate\Support\Collection;
use Laravel\Dusk\Browser;

abstract class BrowserJob extends BaseJob
{
    use BrowserScaffold;

    public function __construct(User $user = null, array $tags = [])
    {
        parent::__construct($user, $tags);
    }

    /**
     * @param User|null $user
     * @throws \Throwable
     */
    public function handle(User $user = null)
    {
        $this->browse(function (Browser $browser) use ($user) {
            if ($this->tasks instanceof Collection) {
                $this->tasks->each(function(BrowserTask $task) use ($user, $browser) {
                    if ($this->debug) \Log::debug("Starting '".$task->getName()."'..");
                    $task->run($this->user ?: $user, $browser);
                    if ($this->debug) \Log::debug("Done with '".$task->getName()."'.");
                    sleep(2);
                });
            }
        });
        $this->closeAll();
    }
}