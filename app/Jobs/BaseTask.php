<?php

namespace App\Jobs;

use App\Traits\BrowserScaffold;
use App\User;
use Laravel\Dusk\Browser;

abstract class BaseTask
{
    use BrowserScaffold;

    public $params;

    public function __construct($params = null)
    {
        $this->params = $params;
        $this->setup();
    }

    public function setup(){}

    /**
     * @param User|null $user
     */
    abstract public function execute(User $user = null);

    public function getName()
    {
        $className = get_class($this);
        return substr($className, strrpos($className, '\\') + 1);
    }

}