<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
        'area'
    ];
       
    public $incrementing = false;

    protected $keyType = 'uuid';

    protected static function boot()
    {
        parent::boot();
    
        // Before Create Hook
        static::creating(function ($model) {
            try {
                $model->id = (string) Str::uuid(); // generate uuid
                // Change id with your primary key
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
            if (empty($model->status)) {
                $model->status = 0;
            }
            if (empty($model->creator_id)) {
                $model->creator_id = Auth::id();
            }
            if (empty($model->modifier_id)) {
                $model->modifier_id = Auth::id();
            }
        });
    
        // Before Update Hook
        static::updating(function ($model) {
            if (empty($model->status)) {
                $model->status = 0;
            }
            if (empty($model->modifier_id)) {
                $model->modifier_id = Auth::id();
            }
        });
    }



    // Query Builder version
    public function get_data(){
        $data = Room::select(
            'rooms.id',
            'rooms.room_type_id',
            'rooms.asset_id',
            'room_types.name as room_type_name',
            'rooms.name',
            'rooms.area',
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