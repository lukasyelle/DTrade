<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $fillable = ['number_shares', 'order_type', 'executed', 'user_id', 'stock_id', 'automation_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function automation()
    {
        return $this->belongsTo(Automation::class);
    }

    public function execute()
    {

    }
}
