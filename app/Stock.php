<?php

namespace App;

class Stock
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
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'remember_token',
    ];
}
