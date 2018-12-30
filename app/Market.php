<?php

namespace App;

use RadicalLoop\Eod\Config as EodConfig;
use RadicalLoop\Eod\Api\Stock as Connection;

class Market
{
    protected $connection;

    function __construct()
    {
        $config = new EodConfig(env('EOD_API_KEY'));
        $this->connection = new Connection($config);
    }

}