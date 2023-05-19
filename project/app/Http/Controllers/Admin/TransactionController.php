<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\RoomType;
use App\Transaction;
use App\TransactionDetail;
use App\Traits\RespondsWithHttpStatus;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    use RespondsWithHttpStatus;
    
    private $controller = 'transaction';

    private function title(){
        return __('main.transaction_list');
    }

    public function __construct() {
        $this->middleware('auth');
    }     

    public function index(){
        if (!Auth::user()->can($this->controller.'-list')){
            return view('backend.errors.401')->with(['url' => '/admin']);
        }

        $room_types = RoomType::whereIn('id', function ($query) {
            $query->select('room_type_id')
                ->from('transaction_details')
                ->join('rooms', 'transaction_details.room_id', '=', 'rooms.id')
                ->distinct();
        })->get();

        return view('backend.'.$this->controller.'.list', compact('room_types'))->with(array('controller' => $this->controller, 'pages_title' => $this->title()));
    }

    public function get_data(Request $request){
        if (!Auth::user()->can($this->controller.'-list')){
            return $this->unauthorizedAccessModule();
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
        if (!Auth::user()->can($this->controller.'-edit')){
            return $this->unauthorizedAccessModule();
        }  

        $res = Transaction::with('transactionDetails.room')->with('transactionExtraCharges.extra_charge')->find($id);
        if(!$res){
            return $this->errorNotFound(null);
        }

        return $this->ok($res, null);
    }

    public function update_status(Request $request, $id){
        if (!Auth::user()->can($this->controller.'-edit')){
            return $this->unauthorizedAccessModule();
        }  
        $res = Transaction::find($id);
        if(!$res){
            return $this->errorNotFound(null);
        }

        $staff = new Staff;
        $staffID = $staff->get_staff_id();
        if ($staffID) {
            $res->staff_id = $staffID;
        }

        $transaction = Transaction::find($id);
        if ($transaction->status_transaction == "finish") {
            return $this->badRequest("Tidak dapat merubah status , karena transkasi sudah selesai");        
        }
        
        $transactionDetails = TransactionDetail::where('transaction_id', $id)->get();
        foreach ($transactionDetails as $transactionDetail) {
            $product = Product::find($transactionDetail->product_id);
            if ($product->stock < $transactionDetail->quantity) {
                return response()->json(['message' => 'Not enough stock.'], 403);
            }
            $product->stock -= $transactionDetail->quantity;
            $product->save();
        }
        
        $res->status_transaction = $request->status;
        $res->modifier_id = Auth::user()->id;
        $res->save();  
        
        return $this->ok($res, null);        
    }

    public function delete($id){
        if (!Auth::user()->can($this->controller.'-delete')){
            return $this->unauthorizedAccessModule();
        }  

        $res = Transaction::find($id);
        if (!$res) {
            return $this->errorNotFound(null);
        }
        $res->delete();
        TransactionDetail::where('transaction_id', $id)->delete();

        return $this->deleted("Data deleted successfully");
    }

    public function delete_batch(Request $request) {
        if (!Auth::user()->can($this->controller.'-delete')){
            return $this->unauthorizedAccessModule();
        }  

        $ids = $request->input('id');
        Transaction::whereIn('id', $ids)->delete();
        TransactionDetail::whereIn('transaction_id', $ids)->delete();

        return $this->deleted("Data deleted successfully");
    }

}
