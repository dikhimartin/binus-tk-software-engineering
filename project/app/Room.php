<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    Use Uuid;

    protected $table = 'rooms';
    
    protected $fillable = [
        'asset_id', 
        'room_type_id', 
        'name', 
        'description',
        'status',
        'province_id', 
        'city_id', 
        'subdistrict_id', 
        'area'
    ];
       
    public $incrementing = false;

    protected $keyType = 'uuid';
}