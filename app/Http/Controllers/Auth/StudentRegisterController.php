<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Student;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentRegisterController extends Controller
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
    protected $redirectTo = '/student-home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:student');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function showRegistrationForm()
    {
        return view('auth.student-register');
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'code' => ['required', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:students'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Student
     */
    protected function create(array $data)
    {
        $code = Student::where('code', $data['code'])->get();

        if (!$code->isEmpty()) {
            foreach ($code as $co) {
                $id = $co->id;
            }
            foreach ($code as $co) {

                $level =  $co->level;
            }
            foreach ($code as $co) {

                $speciality =  $co->speciality;
            }
            foreach ($code as $co) {

                $is_working =  $co->is_working;
            }
            foreach ($code as $co) {

                $rate =  $co->rate;
            }
            $student = Student::findOrFail($id);

            $student->id = $id;
            $student->level = $level;
            $student->code = $data['code'];
            $student->name = $data['name'];
            $student->email = $data['email'];
            $student->password = Hash::make($data['password']);
            $student->save();
            return  $student;
        }
    }
    public function register(Request $request)
    {
        $code = Student::where('code', $request->code)->get();
        if (!$code->isEmpty()) {
            $this->validator($request->all())->validate();


            event(new Registered($user = $this->create($request->all())));

            $this->guard()->login($user);
            return $this->registered($request, $user)
                ?: redirect($this->redirectPath());
        } else {
            $errorNotfoundId = "Your Code Not Valid or Not Found ";

            return redirect()->back()->withErrors($errorNotfoundId)->withInput();
        }
    }
    protected function guard()
    {
        return Auth::guard('student');
    }
    protected function registered(Request $request, $user)
    {
        //
    }
}
