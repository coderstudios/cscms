<?php
/**
 * Part of the CSCMS package by Coder Studios.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the terms of the MIT license https://opensource.org/licenses/MIT
 *
 * @version    1.0.0
 *
 * @author     Coder Studios Ltd
 * @license    MIT https://opensource.org/licenses/MIT
 * @copyright  (c) 2022, Coder Studios Ltd
 *
 * @see       https://www.coderstudios.com
 */

namespace CoderStudios\CsCms\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use CoderStudios\CsCms\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use View;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest');
        $this->request = $request;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        if (!$this->request->config['user_allow_registration']) {
            return redirect()->route('frontend.index');
        }
        $vars = [
            'required_username' => $this->request->config['user_require_username'],
        ];
        $view = 'cscms::frontend.default.auth.register';
        if (View::exists(config('cscms.coderstudios.theme').'.auth.register')) {
            $view = config('cscms.coderstudios.theme').'.auth.register';
        }

        return view($view, compact('vars'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validation = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ];
        if ($this->request->config['user_require_username']) {
            $validation['username'] = 'required|max:255|unique:users';
        }

        return Validator::make($data, $validation);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return User
     */
    protected function create(array $data)
    {
        $user_data = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'user_role_id' => 1,
        ];
        $user_data['username'] = substr(md5($user_data['email']), 0, 8);
        if ($this->request->config['user_require_username']) {
            $user_data['username'] = $data['username'];
        }

        return User::create($user_data);
    }
}
