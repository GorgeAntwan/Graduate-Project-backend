<?php

namespace App\Http\Controllers;

use App\Assistant;
use App\ActivatedCourses;
use App\Course;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterAuthRequest;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthAssistantController extends Controller
{
    //
      //
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:assistant-api', ['except' => ['login','register','restPassword']]);
    }

    
    protected function validatorRestPassword(array $data)
    {
        return Validator::make($data, [
            'code' => ['required', 'min:3','max:100'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'newpassword' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
    public function restPassword(Request $request)
    {   

        try {
            $this->validatorRestPassword($request->all())->validate();
            } catch (\Throwable $th) {
                return response()->json($this->validatorRestPassword($request->all())->errors(), 200);
            }
        $student = Assistant::Where('code','=',$request->code)->get();
        if($student !='[]'){
            foreach( $student as $k){
                    
                
            
            if( $k->email== $request->email){
                $k->password = Hash::make($request->newpassword);
                $k->password;
                $k->save();
                return response()->json(['massage' => 'Successfully change password'], 200);
            }else{
                return response()->json(['error' => 'enter valid email'], 401);
            }
           }
        }else{

            return response()->json(['error' => 'code not found'], 401);
        }
        
        
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'code' => ['required', 'min:3','max:100'],
            'name' => ['required', 'string', 'min:5','max:35'],
            'email' => ['required', 'string', 'email', 'max:255' ],
            'phone' => ['digits:11','numeric'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
    protected function validator_login(array $data)
    {
        return Validator::make($data, [
        
            'email' => ['required', 'string', 'email', 'max:255' ],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }
    public $loginAfterSignUp = true;
    public function register(Request $request)
    {   
        try {
        $this->validator($request->all())->validate();
        } catch (\Throwable $th) {
            return response()->json($this->validator($request->all())->errors(), 200);
        }

        $user = Assistant::Where('code','=',$request->code)->get();
        if ($user!="[]") {
        
            //return $user;
            foreach ($user as $k) {
                $id =$k->id;
            }
            $assistant = Assistant::find($id);

            if (!Assistant::Where('email','=',$request->email)->get()->isEmpty()) {

                return response()->json(['error' => 'email already exist'], 401);
            
            }else{
                $assistant->name = $request->name;
                $assistant->email = $request->email;
                $assistant->password = bcrypt($request->password);
                if($request->phone){
                    $assistant->phone = $request->phone;
                }
                $assistant->save();
            }
           

        }else{
            return response()->json(['error' => 'Unauthorized !! your code not found enter correct code '], 401);
        }
 
        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }
 
        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }




    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function login(Request $request)
    { 
        try {
        $this->validator_login($request->all())->validate();
        } catch (\Throwable $th) {
            return response()->json($this->validator_login($request->all())->errors(), 200);
        }
        $credentials = $request->only('email', 'password');

        if (! $token = auth('assistant-api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 200);
        }
        
        return $this->respondWithToken($token);
    }
    

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
