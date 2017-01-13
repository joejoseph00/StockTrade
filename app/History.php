<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'symbol',
        'high',
        'open',
        'close',
        'low',
        'volume',
        'unadjhigh',
        'unadjlow',
        'unadjopen',
        'unadjclose',
        'timestamp',
    ];

    public $timestamps = false;

    protected $table = 'history';

}
