<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{

    public function stocks()
    {
        return $this->belongsToMany(Stock::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
