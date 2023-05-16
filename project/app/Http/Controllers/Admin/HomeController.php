<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Traits\RespondsWithHttpStatus;
use PeterColes\Countries\CountriesFacade;
use DB;
use App\User;
use App\Product;
use Illuminate\Support\Facades\Auth;
use Zizaco\Entrust\EntrustFacade as Entrust;

class HomeController extends Controller
{

    use RespondsWithHttpStatus;

    private $controller = 'home';
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function title(){
        return __('main.dashboard');
    }

    public function index(){
      $controller =$this->controller;
      $pages_title="Dashboard";
      $page_active='dashboard';

      $user = new User;
      $datas = $user->get_data();
      $data_user = $datas->find(Auth::user()->id);

      $product = new Product;
      $products = $product->get_data()->get();

      return view('backend.home',compact('controller','page_active','pages_title','data_user','products'));
    }
}
