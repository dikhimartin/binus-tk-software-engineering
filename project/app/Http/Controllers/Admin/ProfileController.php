<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Traits\RespondsWithHttpStatus;
use App\User;
use App\Asset;

class ProfileController extends Controller
{

    use RespondsWithHttpStatus;

    private $controller = 'profile';

    private function title(){
        return __('main.profile');
    }

	public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $user = new User;
        $datas = $user->get_data();
        $data_user = $datas->find(Auth::user()->id);
        return view('backend.'.$this->controller.'.index', compact('data_user'))->with(array('controller' => $this->controller, 'pages_title' => $this->title()));
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
            'password_confirmation' => 'sometimes|required_with:password|same:password'
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

    public function update(Request $request){
        $id = Auth::user()->id;

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
            // old password validation
            if (empty($request->old_password)){
                return $this->badRequest("old_password_required");
            }
            if (!Hash::check($request->old_password, Auth::user()->password)) {
                return $this->unauthorized("old_password_false");
            }
            $user->password = bcrypt($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->save();  

        return $this->ok($user, null);
    }
}
