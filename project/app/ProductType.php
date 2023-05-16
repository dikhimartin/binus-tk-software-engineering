<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class ProductType extends Model
{
    Use Uuid;

    protected $table = 'product_types';
    protected $fillable = [
        'name', 'description','status'
    ];
       
    public $incrementing = false;

    protected $keyType = 'uuid';
}
