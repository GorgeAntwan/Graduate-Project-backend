<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Config;
use App\Student;
use App\Assistant;
use App\ActivatedCourses;
use App\TotalAssistants;

class AssistantController  extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:assistant');
    }

    


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       // echo  Auth::User();
          Auth::User()->id;
          $assitant =Auth::User()->ActivatedCourses;
          foreach ($assitant as $s) {
            // echo $s;
          }
          
        return view('Assistant',compact('assitant'));
    }
    public function home()
    {
        return view('auth.assistant-home');
    }
    public function  get_all_information_about_current_assistant()
    {
        $assistant_auth = Auth::User();
        $assitant = Assistant::find(Auth::User()->id);
        $assitant->teaching_hours = $this->computeTeachingHour();
        $assitant->save();
        return   $assitant;
    }
    public function computeTeachingHour()
    {
        $assistant_auth = Auth::User();
        $assitant = Assistant::find(Auth::User()->id);
        $activatecourse_all = [];
        foreach ($assitant->ActivatedCourses as $k) {
            $activatecourse = ActivatedCourses::find($k->id);
            $activatecourse_all[] = $activatecourse->Course->practical_hours;
        }
        return  array_sum($activatecourse_all);
    }
     public function get_all_course()
    {
        $assistant_auth = Auth::User();
        $assitant = Assistant::find(Auth::User()->id);
        $activatecourse_all = [];
        foreach ($assitant->ActivatedCourses as $k) {
            $activatecourse = ActivatedCourses::find($k->id);
            $activatecourse_all[] = $activatecourse;
        }
        return  $activatecourse_all;
    } 
     
    public function   get_all_total_assistant()
    {
        $assistant_auth = Auth::User();
        $assitant = Assistant::find(Auth::User()->id);
        $total_all = [];
        foreach ($assitant->ActivatedCourses as $k) {

            $total_get = TotalAssistants::where('activated_courses_id', '=',  $k->id)->get();
            if (sizeof($total_get)) {
                $total_all[] = $total_get;
            }
        }
           
        $All_total_assistant = [];
        foreach ($total_all as $kk) {

            foreach ($kk as $k) {
                
                $total[] = ActivatedCourses::find($k->activated_courses_id)->course_code;
                $total[] = ($k->t_16 * 100) / ($k->count * 5);
                $total[] = ($k->t_17 * 100) / ($k->count * 5);
                $total[] = ($k->t_18 * 100) / ($k->count * 5);
                $total[] = ($k->t_19 * 100) / ($k->count * 5);
                $total[] = ($k->t_20 * 100) / ($k->count * 5);

                $All_total_assistant[] = $total;
            }
        }
        return  $All_total_assistant;
    }
}
