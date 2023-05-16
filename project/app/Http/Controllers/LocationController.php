<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\RespondsWithHttpStatus;
use App\Province;
use App\City;
use App\Subdistrict;
use App\Village;

class LocationController extends Controller
{
    use RespondsWithHttpStatus;

    public function province(){
        $res = Province::all();
        return $this->ok($res, null);
    }

    public function city($province_id){
        $res = City::where('province_id', $province_id)->get();
        return $this->ok($res, null);
    }

    public function subdistrict($city_id){
        $res = Subdistrict::where('city_id', $city_id)->get();
        return $this->ok($res, null);
    }

    public function village($subdistrict_id){
        $res = Village::where('subdistrict_id', $subdistrict_id)->get();
        return $this->ok($res, null);
    }
}
