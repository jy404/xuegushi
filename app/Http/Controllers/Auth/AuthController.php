<?php

namespace App\Http\Controllers\Auth;

use DB;
use Hash;
use App\User;
use App\Models\Employee;
use App\Role;
use Validator;
use Eloquent;
use Redirect;
use App\Helpers\MailUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
        $this->redirectTo = URL::previous();
    }
    public function redirectPath(){
        info(URL::previous());
        return URL::previous();
    }
    public function showRegistrationForm()
    {
        $roleCount = Role::count();
		if($roleCount != 0) {
            return view('auth.register');
		} else {
			return view('errors.error', [
				'title' => 'Migration not completed',
				'message' => 'Please run command <code>php artisan db:seed</code> to generate required table data.',
			]);
		}
    }
    
    public function showLoginForm()
    {
		$roleCount = Role::count();
		if($roleCount != 0) {
			$userCount = User::count();
			if($userCount == 0) {
				return redirect('register');
			} else {
				return view('auth.login');
			}
		} else {
			return view('errors.error', [
				'title' => 'Migration not completed',
				'message' => 'Please run command <code>php artisan db:seed</code> to generate required table data.',
			]);
		}
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
     * @return mixed
     */
    protected function create(array $data)
    {
        // TODO: This is Not Standard. Need to find alternative
        Eloquent::unguard();
        
        $employee = Employee::create([
            'name' => $data['name'],
            'designation' => "Super Admin",
            'mobile' => "8888888888",
            'mobile2' => "",
            'email' => $data['email'],
            'gender' => 'Male',
            'dept' => "1",
            'city' => "Pune",
            'address' => "Karve nagar, Pune 411030",
            'about' => "About user / biography",
            'date_birth' => date("Y-m-d"),
            'date_hire' => date("Y-m-d"),
            'date_left' => date("Y-m-d"),
            'salary_cur' => 0,
        ]);
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'context_id' => '0',
            'type' => "Employee",
        ]);
        $role = Role::where('name', 'NORMAL')->first();
        $user->attachRole($role);
        MailUtil::UserCreate($data['name'],$data['email']);
        return $user;
    }
    /**
     * @param $request
     * login
     * @return mixed
     */
    public function login(Request $request)
    {
        $_email     = $request->input('email');
        $_password = $request->input('password');
        $_user = User::where('email', $_email)->first();
        $_error_messages = [];
        $this->validateLogin($request);
        if($_user){
            if(!Hash::check($_password,$_user->password)){
                $_error_messages['password'] = '密码不匹配，请重现输入';
                return back()->withInput()->withErrors($_error_messages);
            }
        }else{
            $_error_messages['email'] = '邮箱'.$_email.'尚未注册';
            return back()->withInput()->withErrors($_error_messages);
        }
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();
        User::where('email',$_email)->update(['last_login_ip'=>@request()->ip()]);
        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        if (Auth::guard($this->getGuard())->attempt($credentials, $request->has('remember'))) {
            info('ok---');
//            return $this->handleUserWasAuthenticated($request, $throttles);
            if ($throttles) {
                $this->clearLoginAttempts($request);
            }

            if (method_exists($this, 'authenticated')) {
                return $this->authenticated($request, Auth::guard($this->getGuard())->user());
            }

            if(request()->session()->has('login_back_fallback')) {
                $_tmp_url = request()->session()->get('login_back_fallback');
                request()->session()->forget('login_back_fallback');

                return redirect()->to($_tmp_url);
            }else{
                return redirect()->intended($this->redirectPath());
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles && ! $lockedOut) {
            info('ok1---');
            $this->incrementLoginAttempts($request);
        }

        return $this->sendFailedLoginResponse($request);
    }
    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        $this->redirectAfterLogout = url()->previous();
        Auth::guard($this->getGuard())->logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }
}
