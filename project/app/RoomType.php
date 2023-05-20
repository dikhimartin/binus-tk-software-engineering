<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class RoomType extends Model
{
    Use Uuid;

    protected $table = 'room_types';
    protected $fillable = [
        'name', 'description','status'
    ];
       
    public $incrementing = false;

    protected $keyType = 'uuid';

    public function rooms(){
        return $this->hasMany(Room::class);
    }

}
