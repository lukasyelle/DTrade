<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ticker extends Model
{

    protected $fillable = ['symbol', 'data'];

    private $market;
    public $symbol;

    public function __construct()
    {
        parent::__construct();
        $this->market = new Market();
    }

    private function getLastUpdatedTimestamp()
    {
        $updatedAtColumn = $this->getUpdatedAtColumn();
        return $this->$updatedAtColumn;
    }

    private function saveHistory()
    {
        TickerHistory::create([
            'ticker_id' => $this->id,
            'data'      => $this->data,
            'as_of'     => $this->getLastUpdatedTimestamp()
        ]);
    }

    public static function fetch($symbol)
    {
        $ticker = Ticker::where('symbol', $symbol)->first();
        if ($ticker == null) {
            DB::table('tickers')->insert([
                'symbol' => $symbol,
                'created_at' => Carbon::now(),
            ]);
            return Ticker::fetch($symbol);
        }
        return $ticker;
    }

    public function updateData()
    {
        $updatedAt = $this->getLastUpdatedTimestamp();
        $currentTime = $this->freshTimestamp();
        if ($updatedAt == null || $currentTime->diffInMinutes($updatedAt) > 30) {
            if ($this->data) {
                $this->saveHistory();
            }
            $this->data = $this->market->realTime($this['symbol']);
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
