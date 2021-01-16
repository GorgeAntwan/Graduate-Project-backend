<?php

    namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use App\Providers\RouteServiceProvider;
    use App\Assistant;
    use Illuminate\Foundation\Auth\RegistersUsers;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Auth\Events\Registered;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class AssistantRegisterController extends Controller
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
        protected $redirectTo = '/assistant-home';

        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct()
        {
            $this->middleware('guest:assistant');
        }

        /**
         * Get a validator for an incoming registration request.
         *
         * @param  array  $data
         * @return \Illuminate\Contracts\Validation\Validator
         */
        public function showRegistrationForm()
        {
            return view('auth.assistant-register');
        }
        protected function validator(array $data)
        {
            return Validator::make($data, [
                'code' => ['required', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:assistants'],
                'phone' => ['required', 'regex:/(01)[0-9]{9}/', 'min:11', 'max:11'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
        }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Assistant
     */
        protected function create(array $data)
        {
            $code = Assistant::where('code', $data['code'])->get();

            if (!$code->isEmpty()) {
                foreach ($code as $co) {
                    $id = $co->id;
                }
                foreach ($code as $co) {

                    $department_iddepartment =  $co->department_iddepartment;
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
                $assistnt = Assistant::findOrFail($id);

                $assistnt->id = $id;
                $assistnt->department_iddepartment = $department_iddepartment;
                $assistnt->rate = $rate;
                $assistnt->speciality = $speciality;
                $assistnt->is_working = $is_working;
                $assistnt->code = $data['code'];
                $assistnt->name = $data['name'];
                $assistnt->email = $data['email'];
                $assistnt->phone = $data['phone'];
                $assistnt->password = Hash::make($data['password']);
                $assistnt->save();
                return  $assistnt;
            }
        }
        public function register(Request $request)
        {
            $code = Assistant::where('code', $request->code)->get();
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
            return Auth::guard('assistant');
        }
        protected function registered(Request $request, $user)
        {
            //
        }
    }
