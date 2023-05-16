<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Product;
use App\Asset;
use App\ProductType;
use App\Traits\RespondsWithHttpStatus;
use Yajra\DataTables\Facades\DataTables;


class ProductController extends Controller
{
    use RespondsWithHttpStatus;
    
    private $controller = 'product';

    private function title(){
        return __('main.product_list');
    }

    public function __construct() {
        $this->middleware('auth');
    }     

    public function index(){
        if (!Auth::user()->can($this->controller.'-list')){
            return view('backend.errors.401')->with(['url' => '/admin']);
        }
        $product_type = ProductType::where("status","=", 0)->get();

        return view('backend.'.$this->controller.'.list', compact('product_type'))->with(array('controller' => $this->controller, 'pages_title' => $this->title()));
    }

    public function get_data(Request $request){
        if (!Auth::user()->can($this->controller.'-list')){
            return $this->unauthorizedAccessModule();
        }        

        $product = new Product;
        $datas = $product->get_data();

        return DataTables::of($datas)
        ->filter(function ($query) use ($request) {
            $query->when($request->has('search.value'), function ($q) use ($request) {
                $value = $request->input('search.value');
                $q->where(function ($query) use ($value) {
                    $query->where('products.name', 'like', "%$value%")
                        ->orWhere('products.status', 'like', "%$value%");
                });
            });
        })
        ->addColumn('action', function ($data) {
            // add your action column logic here
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function validate_data(Request $request, $id = null){
        $rules = [
            'name' => 'required',
            'product_type_id' => 'required',
            'purchase_price' => 'required',
            'selling_price' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
    
        return $validator;
    }

    
    public function create(Request $request){
        if (!Auth::user()->can($this->controller.'-create')){
            return $this->unauthorizedAccessModule();
        }  

        $validator = $this->validate_data($request);
        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        }

        // set product assets
        $product_asset = Asset::upload($request->file('asset_id'), "product");
        if (!empty($product_asset) && $product_asset['status'] == 'error') {
            return $this->badRequest($product_asset['message']);
        }
        
        $data = $request->all();
        if (!empty($product_asset['data'])) {
            $data['asset_id'] = $product_asset['data']->id;
        }

        $res = Product::create($data);
        return $this->created($res, null);
    }

    public function update(Request $request, $id){
        if (!Auth::user()->can($this->controller.'-edit')){
            return $this->unauthorizedAccessModule();
        }       
        
        $validator = $this->validate_data($request, $id);
        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        }   

        $res = Product::find($id);
        if(!$res){
            return $this->errorNotFound(null);
        }     

        // set product assets
        $product_asset = Asset::upload($request->file('asset_id'), "product");
        if (!empty($product_asset) && $product_asset['status'] == 'error') {
            return $this->badRequest($product_asset['message']);
        }
        
        $data = $request->all();
        if (!empty($product_asset['data'])) {
            $data['asset_id'] = $product_asset['data']->id;
        }

        $res->fill($data);
        $res->save();        
        return $this->ok($res, null);
    }
    
    public function detail($id){
        if (!Auth::user()->can($this->controller.'-edit')){
            return $this->unauthorizedAccessModule();
        }  

        $product = new Product;
        $datas = $product->get_data();

        $res = $datas->find($id);
        if(!$res){
            return $this->errorNotFound(null);
        }    


        return $this->ok($res, null);
    }

    public function delete($id){
        if (!Auth::user()->can($this->controller.'-delete')){
            return $this->unauthorizedAccessModule();
        }  

        $res = Product::find($id);
        if (!$res) {
            return $this->errorNotFound(null);
        }
        if (!empty($res->asset_id)) {
            Asset::remove($res->asset_id);
        }
        $res->delete();
        
        return $this->deleted("Data deleted successfully");
    }

    public function delete_batch(Request $request) {
        if (!Auth::user()->can($this->controller.'-delete')){
            return $this->unauthorizedAccessModule();
        }  

        $ids = $request->input('id');
        foreach ($ids as $id) {
            $res = Product::find($id);
            if (!$res) {
                return $this->errorNotFound(null);
            }
            if (!empty($res->asset_id)) {
                Asset::remove($res->asset_id);
            }
            $res->delete();
        }

        return $this->deleted("Data deleted successfully");
    }

}
