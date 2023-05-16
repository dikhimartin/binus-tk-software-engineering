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
        'staff_id',
        'transaction_date',
        'description',
        'status_transaction',
        'creator_id',
        'modifier_id'
    ];

    public $incrementing = false;

    protected $keyType = 'uuid';


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
            if (empty($model->status_transaction)) {
                $model->status_transaction = "pending"; 
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

    public function transactionDetails(){
        return $this->hasMany(TransactionDetail::class);
    }

    // Query Builder version
    public function get_data(){
        $data = Transaction::select(
            'transactions.id',
            'transactions.customer_id',
            'transactions.staff_id',
            'creator.name as creator_name',
            'modifier.name as modifier_name',
            'role_creator.display_name as role_creator',
            'role_modifier.display_name as role_modifier',
            'user_customer.name as customer_name',
            'user_staff.name as staff_name',
            'transactions.transaction_date',
            'transactions.description',
            'transactions.status_transaction',
            'transactions.created_at',
            'transactions.updated_at')
        ->leftjoin('customers', 'transactions.customer_id', '=', 'customers.id')
        ->leftjoin('staff', 'transactions.staff_id', '=', 'staff.id')
        ->leftjoin('users as user_customer', 'customers.user_id', '=', 'user_customer.id')
        ->leftjoin('users as user_staff', 'staff.user_id', '=', 'user_staff.id')
       
        ->leftjoin('users as creator', 'transactions.creator_id', '=', 'creator.id')
        ->leftjoin('users as modifier', 'transactions.modifier_id', '=', 'modifier.id')
        
        ->leftjoin('role_user as role_user_creator', 'creator.id', '=', 'role_user_creator.user_id')
        ->leftjoin('roles as role_creator', 'role_user_creator.role_id', '=', 'role_creator.id')

        ->leftjoin('role_user as role_user_modifier', 'modifier.id', '=', 'role_user_modifier.user_id')
        ->leftjoin('roles as role_modifier', 'role_user_modifier.role_id', '=', 'role_modifier.id');
        return $data;
    }

    public function get_data_with_detail(){
        $data = Transaction::select(
            'transactions.id',
            'transactions.customer_id',
            'transactions.staff_id',
            'creator.name as creator_name',
            'modifier.name as modifier_name',
            'role_creator.display_name as role_creator',
            'role_modifier.display_name as role_modifier',
            'user_customer.name as customer_name',
            'user_staff.name as staff_name',
            'transactions.transaction_date',
            'transactions.description',
            'transactions.status_transaction',
            'transactions.created_at',
            'transactions.updated_at',
            \DB::raw('SUM(transaction_details.sub_price) as total_price')
        )
        ->leftJoin('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
        ->leftJoin('customers', 'transactions.customer_id', '=', 'customers.id')
        ->leftJoin('staff', 'transactions.staff_id', '=', 'staff.id')
        ->leftJoin('users as user_customer', 'customers.user_id', '=', 'user_customer.id')
        ->leftJoin('users as user_staff', 'staff.user_id', '=', 'user_staff.id')
        ->leftJoin('users as creator', 'transactions.creator_id', '=', 'creator.id')
        ->leftJoin('users as modifier', 'transactions.modifier_id', '=', 'modifier.id')
        ->leftJoin('role_user as role_user_creator', 'creator.id', '=', 'role_user_creator.user_id')
        ->leftJoin('roles as role_creator', 'role_user_creator.role_id', '=', 'role_creator.id')
        ->leftJoin('role_user as role_user_modifier', 'modifier.id', '=', 'role_user_modifier.user_id')
        ->leftJoin('roles as role_modifier', 'role_user_modifier.role_id', '=', 'role_modifier.id')
        ->groupBy('transactions.id');
    
        return $data;
    }


}

