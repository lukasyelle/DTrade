<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlatformData extends Model
{
    protected $table = 'platform_data';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function portfolio()
    {
        return $this->hasOne(Portfolio::class);
    }
}
