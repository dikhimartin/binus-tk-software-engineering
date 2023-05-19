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

    public function get_data(){
        $data = Transaction::select(
             'transactions.id',
             'transactions.sort',
             'booker.id as booker_id',
             'rooms.id as room_id',
             'room_types.id as room_type_id',
             'transactions.transaction_code',
             'booker.name as booker_name',
             'rooms.name as room_name',
             'room_assets.absolute_path as assets_absolute_path',
             'room_assets.relative_path as assets_relative_path',
             'room_assets.file_name as assets_name',
             'room_types.name as room_type_name',
             'transactions.transaction_date',
             'transaction_details.check_in_date',
             'transaction_details.check_out_date',
             'transaction_details.days',
             'transactions.total_room_price',
             'transactions.total_extra_charge',
             'transactions.final_total',
             'transactions.created_at',
             'transactions.updated_at',
             'transactions.creator_id',
             'transactions.modifier_id'
            )
            ->leftjoin('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->leftjoin('rooms', 'transaction_details.room_id', '=', 'rooms.id')
            ->leftjoin('assets as room_assets', 'rooms.asset_id', '=', 'room_assets.id')
            ->leftjoin('users as booker', 'transactions.customer_id', '=', 'booker.id')
            ->leftjoin('room_types', 'rooms.room_type_id', '=', 'room_types.id');
    
        return $data;
    }


}
