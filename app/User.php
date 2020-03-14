<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function platforms()
    {
        return $this->hasMany(PlatformData::class);
    }

    public function portfolios()
    {
        return $this->platforms()->select('platform', 'updated_at')->get();
    }

    public function dataSource()
    {
        return $this->hasOne(AlphaVantageApi::class);
    }
}
