<?php

namespace App;

use RadicalLoop\Eod\Api\Stock as Connection;
use RadicalLoop\Eod\Config as EodConfig;

class Market
{
    protected $connection;

    public function __construct()
    {
        $config = new EodConfig(env('EOD_API_KEY'));
        $this->connection = new Connection($config);
    }

    public function realTime($symbol)
    {
        return collect(json_decode($this->connection->realTime($symbol)->json()));
    }

    public function eod($symbol)
    {
        return collect(json_decode($this->connection->eod($symbol)->json()));
    }
}
