<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class ExtraCharge extends Model
{
    Use Uuid;

    protected $table = 'extra_charges';
    protected $fillable = [
        'name', 'description','status'
    ];
       
    public $incrementing = false;

    protected $keyType = 'uuid';
}
