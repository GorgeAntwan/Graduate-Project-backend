<?php

namespace App\Http\Middleware;

use App\ActivatedCourses;
use Closure;

class isCoordinator
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
          
        $activated_course =ActivatedCourses::find($request->route()->parameter('activate_course_id'));
        if(auth()->user()->id!=$activated_course->coordinator_id){
          
            return response()->json(['error' =>'unauthrized your not coordinator for this is course'],401);
        
        }
         
        return $next($request);
    }
}
