<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phpml\Estimator;

class TrainedStockModel extends Model
{
    protected $fillable = ['stock_id', 'model'];

    public static function store(Estimator $estimator, Stock $stock)
    {
        $model = base64_encode(serialize($estimator));
        $result = self::create([
            'stock_id'  => $stock->id,
            'model'     => $model,
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
