<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Config;

class DoctorLoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    use AuthenticatesUsers;
    public function __construct()
    {
        $this->middleware('guest:doctor', ['except' => ['logout']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     

     public function showLoginForm()
     {
         return view('auth.doctor-login');
     }
    protected function guard()
    {
        return Auth::guard('doctor');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|email',
            'password' => 'required|min:6'
        ]);
    }
    public function logout()
    {
        Auth::guard('doctor')->logout();
        return redirect('/');
    }

}
