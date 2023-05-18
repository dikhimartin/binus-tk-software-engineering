<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomFacility extends Model
{

    protected $table = 'room_facilities';
    
    protected $fillable = [
        'room_id', 
        'facility_id'
    ];
       
    public $incrementing = false;
    public $timestamps = false;
}