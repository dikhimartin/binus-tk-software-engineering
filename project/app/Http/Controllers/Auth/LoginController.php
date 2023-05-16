<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $field = $this->field($request);
        $value = $request->input('email_or_username');
        
        return [
            $field => $value,
            'password' => $request->input('password'),
        ];
    }

    /**
     * Determine if the request field is email or username.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function field(Request $request)
    {
        $value = $request->input('email_or_username');
        return filter_var($value, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $field = $this->field($request);

        $rules = [
            'email_or_username' => "required",
            'password' => 'required',
        ];

        // Only add captcha validation if in production environment
        if (config('app.env') === 'production') {
            $rules['g-recaptcha-response'] = 'required|captcha';
            $messages['g-recaptcha-response.required'] = 'Please verify that you are not a robot.';
        }

        $this->validate($request, $rules);
    }

    public function username()
    {
        return 'email_or_username';
    }
}
