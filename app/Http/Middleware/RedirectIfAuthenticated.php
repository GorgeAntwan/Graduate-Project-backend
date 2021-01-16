<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{

    public function handle($request, Closure $next, $guard = null)
    {
        switch ($guard) {
            case 'doctor':
                if (Auth::guard($guard)->check()) {
                    return redirect()->route('doctor.dashboard');
                }
                break;
            case 'assistant':
                if (Auth::guard($guard)->check()) {
                    return redirect()->route('assistant.dashboard');
                }
                break;
            case 'student':
                if (Auth::guard($guard)->check()) {
                    return redirect()->route('student.dashboard');
                }
                break;

            default:
                if (Auth::guard($guard)->check()) {
                    return redirect('/home');
                }
                break;
        }
        return $next($request);
    }
}
