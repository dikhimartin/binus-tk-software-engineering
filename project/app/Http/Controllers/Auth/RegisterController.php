<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\RoleUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messages = [
            'password.required' => __('validation.required', ['attribute' => 'password']),
            'password.regex' => __('passwords.rules_password_description'),
        ];
    
        $rules = [
            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],            
            'password' => [
                'required',
                'string',
                'min:6', // must be at least 6 characters
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,}$/'
            ],
        ];
        
        // add captcha validation rule if in production environment
        if (config('app.env') === 'production') {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }
    
        return Validator::make($data, $rules, $messages);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $guest_role = 4; // guest

        $email = $data['email'];
        $username = Str::before($email, '@'); // Generate username from email
        $name = Str::title(Str::before($username, '.')); // Generate name from email

        $user = new User;
        $user->email          = $email;
        $user->username       = $username;
        $user->name           = $name;
        $user->role_id        = $guest_role;
        $user->status         = "Y";
        $user->password       = bcrypt($data['password']);
        $user->save();

        $role_user = new RoleUser;
        $role_user->user_id = $user->id;
        $role_user->role_id = $guest_role;
        $role_user->save();
        
       return $user;
    }
}
