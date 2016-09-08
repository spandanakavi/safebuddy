<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(Request $request)
    {
        $data = $request->all();

        User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'ace_number' => isset($data['ace_number']) ? $data['ace_number'] : null,
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'project_id' => isset($data['project_id']) ? $data['project_id'] : null,
            'is_parent' => $data['is_parent'],
            'child_email' => isset($data['child_email']) ? $data['child_email'] : null,
            'mobile' => isset($data['mobile']) ? $data['mobile'] : null
        ]);
        return redirect('/home');
    }
}
