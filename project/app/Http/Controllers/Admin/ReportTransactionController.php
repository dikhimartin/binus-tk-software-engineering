<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Transaction;
use App\RoomType;

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
                        ->orWhere('rooms.name', 'like', "%$value%")
                        ->orWhere('booker.name', 'like', "%$value%");
                });
            });
            $query->when($request->filled('room_type_id'), function ($q) use ($request) {
                $q->where('room_type_id', '=', $request->input('room_type_id'));
            });
            $query->when($request->filled('start_date') && $request->filled('end_date'), function ($q) use ($request) {
                $start_date = $request->input('start_date');
                $end_date = $request->input('end_date');
                $q->whereBetween('transactions.transaction_date', [$start_date, $end_date]);
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

    public function chart(Request $request){
        $roomTypes = RoomType::with([
            'rooms.transactionDetails.transaction' => function ($query) {
                $query->selectRaw('*, 
                    SUM(total_room_price) as total_room_price,
                    SUM(total_extra_charge) as total_extra_charge,
                    SUM(final_total) as final_total')
                    ->groupBy('id');
            }
        ])->whereHas('rooms.transactionDetails')->get();

        $result = $roomTypes->map(function ($roomType) {
            $roomTypeData = $roomType->toArray();
            $transactionData = $roomType->rooms->flatMap(function ($room) {
                return $room->transactionDetails->pluck('transaction');
            })->toArray();
            
            $roomTypeData['total_room_price'] = array_sum(array_column($transactionData, 'total_room_price'));
            $roomTypeData['total_extra_charge'] = array_sum(array_column($transactionData, 'total_extra_charge'));
            $roomTypeData['final_total'] = array_sum(array_column($transactionData, 'final_total'));

            return $roomTypeData;
        });
                
        // Return the result as JSON
        return $this->ok($result, null);
    }
    
}
