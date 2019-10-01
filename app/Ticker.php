<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ticker extends Model
{

    private $market;

    public function __construct($symbol = null)
    {
        parent::__construct();
        if ($symbol != null) {
            $table = DB::table('tickers');
            if ($table->where('symbol', $symbol)->get()->isEmpty()) {
                $table->insertGetId([
                        'symbol'    => $symbol,
                        'data'      => json_encode([]),
                    ]);
            }
        }
        $this->market = new Market();
        return $this;
    }

    public function updateData()
    {
        $updatedAtColumn = $this->getUpdatedAtColumn();
        $updatedAt = $this->$updatedAtColumn;
        $currentTime = $this->freshTimestamp();
        if ($updatedAt == null || $currentTime->diffInMinutes($updatedAt) > 30) {
            $this->data = $this->market->realTime($this->symbol);
            $this->save();
        }
    }

    public function history()
    {
        return $this->hasMany(TickerHistory::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

}
