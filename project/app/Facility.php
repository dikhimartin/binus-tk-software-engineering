<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Facility extends Model
{
    Use Uuid;

    protected $table = 'facilities';
    protected $fillable = [
        'name', 'description','status'
    ];
       
    public $incrementing = false;

    protected $keyType = 'uuid';
}
