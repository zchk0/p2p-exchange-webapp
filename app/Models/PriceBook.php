<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceBook extends Model
{
    protected $table = 'price_book';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'date',
        'price',
        'value',
    ];
}
