<?php

namespace App;

use App\Support\Database\CacheQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TickerData extends Model
{
    use CacheQueryBuilder;

    protected $fillable = ['ticker_id', 'open', 'high', 'low', 'close', 'previous_close', 'change', 'change_percent', 'volume', 'is_intraday'];

    public function ticker()
    {
        return $this->belongsTo(Ticker::class);
    }

    public function stock()
    {
        return $this->ticker->stock();
    }

    /**
     * Scope a query to only include End of day data.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEod($query)
    {
        return $query->where('is_intraday', false);
    }

    /**
     * Scope a query to only include intraday data.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIntraday($query)
    {
        return $query->where('is_intraday', true)
                     ->whereDate('ticker_data.created_at', Carbon::today());
    }
}
