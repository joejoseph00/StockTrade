<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'stock_symbol',
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
