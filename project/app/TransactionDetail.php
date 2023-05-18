<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\Uuid;

class TransactionDetail extends Model
{
    Use Uuid;

    protected $table = 'transaction_details';
    protected $fillable = [
        'transaction_id',
        'room_id',
        'days',
        'check_in_date',
        'check_out_date',
        'price',
        'sub_price',
    ];

    public $incrementing = false;

    protected $keyType = 'uuid';
}

