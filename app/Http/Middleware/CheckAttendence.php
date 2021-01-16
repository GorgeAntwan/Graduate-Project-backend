<?php

namespace App\Http\Middleware;

use Closure;
use App\Student;
use Auth;
class CheckAttendence
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next )
    {
        $Student = Auth::User();

        //dd($Student);
        /** foreach ($Student->ActivatedCourses as $s) {
            $attendence = $s->pivot->attendence;
        }
        echo $attendence;  */
       // return $next($request);
    }
}
