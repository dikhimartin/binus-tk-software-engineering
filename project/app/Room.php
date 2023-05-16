<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Room extends Model
{
    Use Uuid;

    protected $table = 'rooms';
    
    protected $fillable = [
        'asset_id', 
        'room_type_id', 
        'name', 
        'price', 
        'description',
        'status',
        'province_id', 
        'city_id', 
        'subdistrict_id', 
        'area'
    ];
       
    public $incrementing = false;

    protected $keyType = 'uuid';

    // Query Builder version
    public function get_data(){
        $data = Room::select(
            'rooms.id',
            'rooms.room_type_id',
            'rooms.asset_id',
            'room_types.name as room_type_name',
            'rooms.name',
            'rooms.description',
            'rooms.price',
            'rooms.status',
            'assets.absolute_path as assets_absolute_path',
            'assets.relative_path as assets_relative_path',
            'assets.file_name as assets_name',
            'rooms.created_at',
            'rooms.updated_at') 
        ->leftjoin('assets', 'rooms.asset_id', '=', 'assets.id')
        ->leftjoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
        ->orderBy('room_type_name', 'desc'); 
        return $data;
    }    
    
}