<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'symbol',
        'name',
        'issuer',
        'type',
        'recordts',
        'statistics',
        'profile',
        'high',
        'low',
        'price',
        'revenue',
        'value',
        'recommendation',
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'created_at',
    ];
}
