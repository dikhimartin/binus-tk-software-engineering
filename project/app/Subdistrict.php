<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    protected $table = 'subdistricts';

    protected $fillable = [
        'id','province_id', 'city_id', 'name'
    ];
       
    public $incrementing = false;
}
