<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Transaction;

// TODO : KURANG GRAFIK DAN FILTER
class ReportTransactionController extends Controller
{
    use RespondsWithHttpStatus;
    
    private $permission = 'report';

    private $controller = 'transaction-report';

    private function title(){
        return __('main.transaction-report');
    }

    public function __construct() {
        $this->middleware('auth');
    }     

    public function index(){
        if (!Auth::user()->can($this->permission.'-list')){
            return view('backend.errors.401')->with(['url' => '/admin']);
        }
        return view('backend.'.$this->permission.'.'.$this->controller.'.index')->with(array('controller' => $this->controller, 'pages_title' => $this->title()));
    }

    public function get_data(Request $request){
        if (!Auth::user()->can($this->permission.'-list')){
            return view('backend.errors.401')->with(['url' => '/admin']);
        }

        $trx = new Transaction;
        $datas = $trx->get_data();

        return DataTables::of($datas)
        ->filter(function ($query) use ($request) {
            $query->when($request->has('search.value'), function ($q) use ($request) {
                $value = $request->input('search.value');
                $q->where(function ($query) use ($value) {
                    $query->where('transactions.transaction_code', 'like', "%$value%")
                        ->orWhere('transactions.transaction_date', 'like', "%$value%");
                });
            });
        })
        ->addColumn('action', function ($data) {
            // add your action column logic here
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function detail($id){
        if (!Auth::user()->can($this->permission.'-list')){
            return $this->unauthorizedAccessModule();
        }  

        $res = Transaction::with('transactionDetails.room')->with('transactionExtraCharges.extra_charge')->find($id);
        if(!$res){
            return $this->errorNotFound(null);
        }

        return $this->ok($res, null);
    }


}
