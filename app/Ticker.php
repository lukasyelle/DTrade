<?php

namespace App;

use App\Jobs\Stocks\DownloadTickerHistory;
use App\Support\Database\CacheQueryBuilder;
use App\Traits\StockIndicators;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ticker extends Model
{
    use StockIndicators, CacheQueryBuilder;

    protected $fillable = ['symbol'];
    protected $hidden = ['data'];

    private $market;
    public $symbol;

    public function __construct()
    {
        parent::__construct();
        $this->market = new Market();
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function data()
    {
        return $this->hasMany(TickerData::class)->orderBy('created_at', 'DESC');
    }

    private function getLastUpdatedTimestamp()
    {
        $updatedAtColumn = $this->getUpdatedAtColumn();

        return $this->$updatedAtColumn;
    }

    public function downloadInitialHistory()
    {
        $rawData = $this->market->eod($this['symbol']);
        foreach ($rawData as $index => $dataPoint) {
            // Get either the previous data point or the current one if its the
            // first entry in the set.
            $previous = $index > 0 ? $rawData[$index - 1] : $dataPoint;
            $previousClose = $previous->close;
            $change = $dataPoint->close - $previousClose;
            // Prevent Division By Zero error
            $previousClose = $previousClose ? $previousClose : 1;
            $changePercent = ($change / $previousClose) * 100;
            $data = [
                'ticker_id'      => $this->id,
                'open'           => $dataPoint->open,
                'high'           => $dataPoint->high,
                'low'            => $dataPoint->low,
                'close'          => $dataPoint->close,
                'volume'         => $dataPoint->volume,
                'previous_close' => $previous->close,
                'change'         => round($change, 2),
                'change_percent' => round($changePercent, 3),
                'created_at'     => Carbon::createFromDate($dataPoint->date),
                'updated_at'     => $this->freshTimestamp(),
            ];
            DB::table('ticker_data')->insert($data);
        }
        $this->setUpdatedAt($this->freshTimestamp());
        $this->save();
    }

    public static function symbolExists($symbol)
    {
        return self::where('symbol', strtoupper($symbol))->exists();
    }

    public static function fetch($symbol, $recurse = true)
    {
        $symbol = strtoupper($symbol);
        $ticker = self::where('symbol', $symbol)->first();
        if ($ticker == null && $recurse) {
            $tickerId = DB::table('tickers')->insertGetId([
                'symbol'     => $symbol,
                'created_at' => Carbon::now(),
            ]);
            Stock::create(['ticker_id' => $tickerId]);
            DownloadTickerHistory::dispatch($symbol);

            return self::fetch($symbol, false);
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
}
