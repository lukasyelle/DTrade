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

    function realTime($symbol) {
        return collect(json_decode($this->connection->realTime($symbol)->json()));
    }

    function eod($symbol) {
        return collect(json_decode($this->connection->eod($symbol)->json()));
    }
}