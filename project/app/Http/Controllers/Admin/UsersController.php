<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Sample;
use App\User;
use App\RoleUser;
use App\Role;
use App\Asset;
use App\Traits\RespondsWithHttpStatus;
use Yajra\DataTables\Facades\DataTables;


class UsersController extends Controller
{
    use RespondsWithHttpStatus;
    
    private $controller = 'users';

    private function title(){
        return __('main.user_list');
    }

    public function __construct() {
        $this->middleware('auth');
    }     

    public function index(){
        if (!Auth::user()->can($this->controller.'-list')){
            return view('backend.errors.401')->with(['url' => '/admin']);
        }
        $roles =  Role::where('roles.id','!=',1)->get();

        return view('backend.user_access.'.$this->controller.'.list', compact('roles'))->with(array('controller' => $this->controller, 'pages_title' => $this->title()));
    }

    public function get_data(Request $request){
        if (!Auth::user()->can($this->controller.'-list')){
            return $this->unauthorizedAccessModule();
        }        

        $user = new User;
        $datas = $user->get_data();

        return DataTables::of($datas)
        ->filter(function ($query) use ($request) {
            $query->when($request->has('search.value'), function ($q) use ($request) {
                $value = $request->input('search.value');
                $q->where(function ($query) use ($value) {
                    $query->where('users.name', 'like', "%$value%")
                        ->orWhere('users.username', 'like', "%$value%")
                        ->orWhere('users.email', 'like', "%$value%")
                        ->orWhere('users.status', 'like', "%$value%")
                        ->orWhereHas('roles', function ($q) use ($value) {
                            $q->where('display_name', 'like', "%{$value}%");
                        });
                });
            });
            if (Auth::user()->role_id != 1) {
                $query->where('users.role_id', '!=', 1);
            }
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
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id)
            ],
            'username' => [
                'required',
                Rule::unique('users')->ignore($id)
            ],
            'password_confirmation' => 'sometimes|required_with:password|same:password',
            'role_id' => 'required',
        ];
    
        // add password rule conditionally
        if($request->method() === 'POST') {
            $rules['password'] = 'required|min:6|confirmed';
        } else {
            $rules['password'] = 'nullable|min:6|confirmed';
        }
    
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
        
        // set image assets
        $asset = Asset::upload($request->file('avatar'), "users");
        if (!empty($asset) && $asset['status'] == 'error') {
            return $this->badRequest($asset['message']);
        }
        
        $data = $request->all();
        if (!empty($asset['data'])) {
            $data['asset_id'] = $asset['data']->id;
        }
        $data['password'] = bcrypt($request->password);

        $user = User::create($data);
        
        $role_user = new RoleUser;
        $role_user->user_id = $user->id;
        $role_user->role_id = $request->role_id;
        $role_user->save();

        return $this->created($user, null);
    }

    public function update(Request $request, $id){
        if (!Auth::user()->can($this->controller.'-edit')){
            return $this->unauthorizedAccessModule();
        }       
        
        $validator = $this->validate_data($request, $id);
        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        }        

        $user = User::find($id);
        if($user == null){
            return $this->errorNotFound(null);
        }     

        // set remove image
        if($request->avatar_remove == "0" || $request->avatar_remove == "1"){
            if(!empty($user->asset_id)){
                Asset::remove($user->asset_id);
            }
            $user->asset_id = null;
        }

        // set image assets
        $asset = Asset::upload($request->file('avatar'), "users", $user->asset_id);
        if (!empty($asset) && $asset['status'] == 'error') {
            return $this->badRequest($asset['message']);
        }
        if (!empty($asset['data'])) {
            $user->asset_id = $asset['data']->id;
        }
        if (!empty($request->password)) {
            $user->password = bcrypt($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->save();   

        // update role user
        if (!empty($request->role_id)) {
            RoleUser::where('user_id', $id)->update(['role_id' => $request->role_id]);
        }

        return $this->ok($user, null);
    }
    
    public function detail($id){
        if (!Auth::user()->can($this->controller.'-edit')){
            return $this->unauthorizedAccessModule();
        }  

        $user = new User;
        $datas = $user->get_data();

        $res = $datas->find($id);
        if($res == null){
            return $this->errorNotFound(null);
        }        
        return $this->ok($res, null);
    }

    public function delete($id){
        if (!Auth::user()->can($this->controller.'-delete')){
            return $this->unauthorizedAccessModule();
        }  

        $user = User::find($id);
        if (!$user) {
            return $this->errorNotFound(null);
        }
        if (!empty($user->asset_id)) {
            Asset::remove($user->asset_id);
        }
        $user->delete();

        return $this->deleted("Data deleted successfully");
    }

    public function delete_batch(Request $request) {
        if (!Auth::user()->can($this->controller.'-delete')){
            return $this->unauthorizedAccessModule();
        }  
    
        $ids = $request->input('id');
        foreach ($ids as $id) {
            $user = User::find($id);
            if (!$user) {
                return $this->errorNotFound(null);
            }
            if (!empty($user->asset_id)) {
                Asset::remove($user->asset_id);
            }
            $user->delete();
        }
    
        return $this->deleted("Data deleted successfully");
    }    

}
