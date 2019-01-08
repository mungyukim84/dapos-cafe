<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Services\UserService;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
      username as username;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/sales';
    private $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(UserService $userService)
    {
        $this->middleware('guest')->except('logout');
        $this->userService = $userService;
    }

    protected function authenticated($user)
    {
        $this->userService->bindKasseToUser();
    }

    public function username()
    {
        return 'name';
    }
  
}
