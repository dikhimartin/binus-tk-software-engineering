<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Facility;
use App\Traits\RespondsWithHttpStatus;
use Yajra\DataTables\Facades\DataTables;


class FacilityController extends Controller
{
    use RespondsWithHttpStatus;
    
    private $controller = 'facility';

    private function title(){
        return __('main.facility_list');
    }

    public function __construct() {
        $this->middleware('auth');
    }     

    public function index(){
        if (!Auth::user()->can($this->controller.'-list')){
            return view('backend.errors.401')->with(['url' => '/admin']);
        }

        return view('backend.'.$this->controller.'.list')->with(array('controller' => $this->controller, 'pages_title' => $this->title()));
    }

    public function get_data(Request $request){
        if (!Auth::user()->can($this->controller.'-list')){
            return $this->unauthorizedAccessModule();
        }        

        return DataTables::of(Facility::query())
            ->filter(function ($query) use ($request) {
                if ($request->has('search.value')) {
                    $query->where(function ($query) use ($request) {
                        $value = $request->input('search.value');
                        $query->where('name', 'like', "%$value%")
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
    
    public function create(Request $request){
        if (!Auth::user()->can($this->controller.'-create')){
            return $this->unauthorizedAccessModule();
        }  

        $data = $request->all();
        $res = Facility::create($data);
        return $this->created($res, null);
    }

    public function update(Request $request, $id){
        if (!Auth::user()->can($this->controller.'-edit')){
            return $this->unauthorizedAccessModule();
        }        

        $res = Facility::find($id);
        if (!$res) {
            return $this->errorNotFound(null);
        }     
        $data = $request->input();
        $res->fill($data);
        $res->save();        
        return $this->ok($res, null);
    }
    
    public function detail($id){
        if (!Auth::user()->can($this->controller.'-edit')){
            return $this->unauthorizedAccessModule();
        }  

        $res = Facility::find($id);
        if (!$res) {
            return $this->errorNotFound(null);
        }        
        return $this->ok($res, null);
    }

    public function delete($id){
        if (!Auth::user()->can($this->controller.'-delete')){
            return $this->unauthorizedAccessModule();
        }  

        $res = Facility::find($id);
        if (!$res) {
            return $this->errorNotFound(null);
        }
        $res->delete();
        
        return $this->deleted("Data deleted successfully");
    }

    public function delete_batch(Request $request) {
        if (!Auth::user()->can($this->controller.'-delete')){
            return $this->unauthorizedAccessModule();
        }  

        $ids = $request->input('id');
        Facility::whereIn('id', $ids)->delete();
        return $this->deleted("Data deleted successfully");
    }

}
