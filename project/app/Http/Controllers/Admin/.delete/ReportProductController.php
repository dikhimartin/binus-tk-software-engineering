<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Support\Facades\Auth;
use App\Product;

class ReportProductController extends Controller
{
    use RespondsWithHttpStatus;
    
    private $permission = 'report';

    private $controller = 'product-report';

    private function title(){
        return __('main.product-report');
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
    
    public function get_best_selling_product(Request $request){
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');
    
        $product = new Product;
        $best_selling = $product->best_selling_products($start_date, $end_date);
    
        return $this->ok($best_selling, null);
    }

}
