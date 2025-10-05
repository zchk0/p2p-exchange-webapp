<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceRate extends Model
{
    protected $table = 'binance';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'asset',
        'date',
        'fiat',
        'price',
        'cbankprice',
        'tradetype',
        'tmethod'
    ];
}
