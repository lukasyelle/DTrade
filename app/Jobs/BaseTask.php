<?php

namespace App\Jobs;

use App\Traits\BrowserScaffold;
use App\User;
use Laravel\Dusk\Browser;

abstract class BaseTask
{
    use BrowserScaffold;

    /**
     * @param User|null $user
     * @return Browser the browser used in the task.
     */
    abstract public function execute(User $user = null);

    public function getName()
    {
        $className = get_class($this);
        return substr($className, strrpos($className, '\\') + 1);
    }

}