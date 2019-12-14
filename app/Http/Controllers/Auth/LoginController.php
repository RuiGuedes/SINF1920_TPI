<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\PickingWaves;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect after login succeeds
     *
     * @return string
     */
    protected function redirectTo()
    {
        if(Auth::check() && Auth::id() === 1) return 'manager/replenishment';
        else if(Auth::check()){
            $unfinished_wave = PickingWaves::getUserPickingWave(Auth::id());
            return ($unfinished_wave == null) ? 'clerk/pickingWaves' : 'clerk/pickingRoute/'. $unfinished_wave->id;
        }

        return '/';
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
