<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    protected $fillable = [
        'id','name', 'province_id'
    ];
       
    public $incrementing = false;
}
