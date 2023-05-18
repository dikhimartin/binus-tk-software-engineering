<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\ExtraCharge;
use App\Traits\RespondsWithHttpStatus;
use Yajra\DataTables\Facades\DataTables;


class ExtraChargeController extends Controller
{
    use RespondsWithHttpStatus;

    public function __construct() {
        $this->middleware('auth');
    }     

    public function get_data(Request $request){
        return DataTables::of(ExtraCharge::query())
            ->filter(function ($query) use ($request) {
                if ($request->has('search.value')) {
                    $query->where(function ($query) use ($request) {
                        $value = $request->input('search.value');
                        $query->where('name', 'like', "%$value%")
                            ->orWhere('price', 'like', "%$value%")
                            ->orWhere('status', 'like', "%$value%")
                            ->orWhere('description', 'like', "%$value%");
                    });
                }
            })
            ->addColumn('action', function ($data) {
                // add your action column logic here
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    

}
