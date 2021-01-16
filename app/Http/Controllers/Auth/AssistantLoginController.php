<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Config;

class AssistantLoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:assistant', ['except' => ['logout']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    use AuthenticatesUsers;

     public function showLoginForm()
     {
         return view('auth.assistant-login');
     }
    protected function guard()
    {
        return Auth::guard('assistant');
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
        Auth::guard('assistant')->logout();
        return redirect('/');
    }

}
