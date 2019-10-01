<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ticker extends Model
{
    protected $fillable = ['symbol'];
    protected $appends = ['data'];

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
            $rawData = $this->market->realTime($this['symbol'])->toArray();
            $rawData['previous_close'] = $rawData['previousClose'];
            $rawData['change_percent'] = $rawData['change_p'];
            TickerData::create(array_merge(['ticker_id' => $this->id], $rawData));
            $this->setUpdatedAt($this->freshTimestamp());
            $this->save();
        }
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function data()
    {
        return $this->hasMany(TickerData::class)->orderBy('created_at', 'DESC');
    }

}
