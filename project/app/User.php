<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\Traits\Uuid;
use DB;


class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait, Uuid;

    protected $table = 'users';

    protected $fillable = [
        'role_id','asset_id','username','name', 'password','email','status'
    ];
    
    public $incrementing = false;

    protected $keyType = 'uuid';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Before Create Hook
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            try {
                $model->id = (string) Str::uuid(); // generate uuid
                // Change id with your primary key
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
            if (empty($model->status)) {
                $model->status = 1; 
            }
            if (empty($model->creator_id)) {
                $model->creator_id = Auth::id(); 
            }
            if (empty($model->modifier_id)) {
                $model->modifier_id = Auth::id(); 
            }
        });
    }


    // Query Builder version
    public function get_data(){
        $users = User::select(
            'users.id',
            'users.username',
            'users.name',
            'roles.display_name',
            'roles.description',
            'users.email',
            'assets.absolute_path as avatar_absolute_path',
            'assets.relative_path as avatar_relative_path',
            'assets.file_name as avatar_name',
            'role_user.role_id',
            'users.created_at',
            'users.updated_at')
        ->leftjoin('assets', 'users.asset_id', '=', 'assets.id')
        ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
        ->leftjoin('roles', 'role_user.role_id', '=', 'roles.id');
        return $users;
    }

    public function asset(){
        return $this->belongsTo(Asset::class);
    }
    
    public function role(){
        return $this->belongsToMany(Role::class);
    }
    
    public function role_user(){
        return $this->hasOne(RoleUser::class);
    }

    // Eloquent version
    // public function get_data(){
    //     $query = User::with(['asset','role_user']);
    //     return $query;
    // }
  
}
