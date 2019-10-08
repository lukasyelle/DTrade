<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phpml\Estimator;

class TrainedStockModel extends Model
{
    protected $fillable = ['stock_id', 'model', 'profit_window'];

    public static function store(Estimator $estimator, Stock $stock, $profit_window)
    {
        $model = base64_encode(serialize($estimator));
        self::create([
            'stock_id'      => $stock->id,
            'model'         => $model,
            'profit_window' => $profit_window,
        ]);
    }

    public function retrieve()
    {
        return unserialize(base64_decode($this->model), [Estimator::class]);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
