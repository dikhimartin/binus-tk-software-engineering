<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\Uuid;

class TransactionExtraCharge extends Model
{
    Use Uuid;

    protected $table = 'transaction_extra_charges';
    protected $fillable = [
        'transaction_id',
        'extra_charge_id',
        'quantity',
        'price',
        'sub_price',
    ];

    public $incrementing = false;

    protected $keyType = 'uuid';
}
