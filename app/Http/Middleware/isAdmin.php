<?php

namespace App\Http\Middleware;

use App\Department;
use Closure;

class isAdmin  
{


    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {    
        $all_id = [];
        $all_user = Department::all();
        foreach ($all_user as $k) {
            $all_id[]= $k->doctor_manager_id ;
            $all_id[]= $k->leader_of_duties_ofQuality;
        }
        
        if(!in_array(auth()->user()->id,$all_id)){
            return response()->json(['error' =>'unauthrized your not admin'],401);
        } 
        return $next($request);
    }
}
