<?php

namespace App\Jobs;

use App\Traits\BrowserScaffold;
use App\User;
use Exception;
use Laravel\Dusk\Browser;

abstract class BrowserTask extends BaseTask
{
    use BrowserScaffold;

    public function __construct($params = null)
    {
        parent::__construct($params);
    }

    /**
     * @param User|null $user
     * @param Browser   $browser
     *
     * @throws Exception
     */
    public function run(User $user = null, Browser $browser = null)
    {
        $this->execute($user, $browser);
        $this->tearDown();
    }

    abstract public function execute(User $user = null, Browser $browser = null);

    public function getName()
    {
        $className = get_class($this);

        return substr($className, strrpos($className, '\\') + 1);
    }
}
