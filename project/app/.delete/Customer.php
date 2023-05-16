<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{

    use  Uuid;

    protected $table = 'customers';

    protected $fillable = [
        'user_id',
        'national_identity_asset_id',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'province_id',
        'city_id',
        'subdistrict_id',
        'address_line',
        'status',
        'creator_id',
        'modifier_id',
        'sort',
        'additional'
    ];
       
    public $incrementing = false;

    protected $keyType = 'uuid';


    // Query Builder version
    public function get_data(){
        $data = Customer::select(
            'customers.id',
            'users.username',
            'users.name',
            'users.email',
            'customers.place_of_birth',
            'customers.date_of_birth',
            'customers.gender',
            'customers.status',
            'provinces.id as province_id',
            'provinces.name as province',
            'cities.id as city_id',
            'cities.name as city',
            'subdistricts.id as subdistrict_id',
            'subdistricts.name as subdistrict',
            'customers.address_line as address_line',
            'avatar.absolute_path as avatar_absolute_path',
            'avatar.relative_path as avatar_relative_path',
            'avatar.file_name as avatar_name',
            'nid.absolute_path as nid_absolute_path',
            'nid.relative_path as nid_relative_path',
            'nid.file_name as nid_name',
            'customers.created_at',
            'customers.updated_at')
        ->leftjoin('users', 'users.id', '=', 'customers.user_id')
        ->leftjoin('assets as avatar', 'users.asset_id', '=', 'avatar.id')
        ->leftjoin('assets as nid', 'customers.national_identity_asset_id', '=', 'nid.id')
        ->join('provinces', 'customers.province_id', '=', 'provinces.id')
        ->join('cities', 'customers.city_id', '=', 'cities.id')
        ->leftjoin('subdistricts', 'customers.subdistrict_id', '=', 'subdistricts.id');
        return $data;
    }

    public function get_customer_id(){
        $customerId = Customer::select()->where("user_id", Auth::user()->id)->pluck("id")->first();
        return $customerId;
    } 


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function nationalIdentityAsset(){
        return $this->belongsTo(Asset::class, 'national_identity_asset_id');
    }

    public function province() {
        return $this->belongsTo(Province::class);
    }

    public function city() {
        return $this->belongsTo(City::class);
    }

    public function subdistrict() {
        return $this->belongsTo(Subdistrict::class);
    }
}
