<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $table = 'roles';
    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = ['id','name', 'display_name', 'description','status', 'created_at', 'updated_at', 'additional'];

    public function permissions() {
        return $this->belongsToMany(Permission::class);
    }
}
