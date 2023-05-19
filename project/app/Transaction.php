<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Transaction extends Model
{
    Use Uuid;

    protected $table = 'transactions';
    protected $fillable = [
        'customer_id',
        'transaction_code',
        'transaction_date',
        'total_room_price',
        'total_extra_charge',
        'final_total',
        'description',
        'creator_id',
        'modifier_id',
    ];

    public $incrementing = false;

    protected $keyType = 'uuid';


    // Before Create Hook
    protected static function boot(){
        parent::boot();

        static::creating(function ($model) {
            try {
                $model->id = (string) Str::uuid(); // generate uuid
                // Change id with your primary key
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
            if (empty($model->sort)) {
                $model->sort = $model->generateSort(); // call generateSort function
            }
            if (empty($model->transaction_code)) {
                $model->transaction_code = $model->generateTransactionCode(); // call generateTransactionCode function
            }
            if (empty($model->customer_id)) {
                $model->customer_id = Auth::id(); 
            }
            if (empty($model->transaction_date)) {
                $model->transaction_date = now(); 
            }
            if (empty($model->creator_id)) {
                $model->creator_id = Auth::id(); 
            }
            if (empty($model->modifier_id)) {
                $model->modifier_id = Auth::id(); 
            }
        });
    }

    public function generateSort(){
        // Example logic: Get the count of last transaction records and increment by 1
        $lastRecord = static::orderBy('created_at', 'desc')->first();
        $sort = $lastRecord ? $lastRecord->sort + 1 : 1;
        
        // Return the generated sort value
        return $sort;
    }

    public function generateTransactionCode(){
        $prefix = "RSV";
        $date = now()->format('m-Y');
        $sort = $this->sort;
        
        // Generate the transaction code with prefix, date, and sort value
        $transactionCode = "{$prefix}-{$date}-{$sort}";
        
        // Return the generated transaction code
        return $transactionCode;
    }

    public function transactionDetails(){
        return $this->hasMany(TransactionDetail::class);
    }
}
