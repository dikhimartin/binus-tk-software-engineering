<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Support\Facades\Auth;


class Staff extends Model
{
    use  Uuid;

    protected $table = 'staff';

    protected $fillable = [
        'user_id',
        'gender',
        'status',
        'creator_id',
        'modifier_id',
        'sort',
        'additional'
    ];    

    public $incrementing = false;

    protected $keyType = 'uuid';

    // Query Builder version
    public function get_data(){
        $data = Staff::select(
            'staff.id',
            'users.username',
            'users.name',
            'users.email',
            'staff.gender',
            'staff.status',
            'avatar.absolute_path as avatar_absolute_path',
            'avatar.relative_path as avatar_relative_path',
            'avatar.file_name as avatar_name',
            'staff.created_at',
            'staff.updated_at')
        ->leftjoin('users', 'users.id', '=', 'staff.user_id')
        ->leftjoin('assets as avatar', 'users.asset_id', '=', 'avatar.id');
        return $data;
    }

    public function get_staff_id(){
        $staffID = Staff::select()->where("user_id", Auth::user()->id)->pluck("id")->first();
        return $staffID;
    } 

}