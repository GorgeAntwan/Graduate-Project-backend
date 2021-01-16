<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Config;
use App\Doctor;
use App\ActivatedCourses;
use App\Total;
use App\TotalAssistants;

class DoctorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:doctor');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // Auth::user()->name //if you want to get ta bout current auth user 
     public function index()
    {     
        return view('Doctor');
    }
    public function home()
    {
        return view('auth.doctor-home');
    }
    public function get_all_information_about_current_doctor()
    {
        $doctor_auth = Auth::User();
        $doctor = Doctor::find(Auth::User()->id);
        $doctor->teaching_hours =$this->computeTeachingHour();
        $doctor->save();
        return   $doctor;
    }
    public function computeTeachingHour()
    {
        $doctor_auth = Auth::User();
        $doctor = Doctor::find(Auth::User()->id);
        $activatecourse_all = [];
        foreach ($doctor->ActivatedCourses as $k) {
            $activatecourse = ActivatedCourses::find($k->id);
            $activatecourse_all[]= $activatecourse->Course->theoritical_hours;
        }
        return  array_sum($activatecourse_all);
    }
     public function get_all_course()
    {
        
       
       try{    
            $doctor = Doctor::find(Auth::User()->id);
            return response()->json($doctor->ActivatedCourses()->get(), 201);
        }catch (\Throwable $th) {
            return response()->json(['message'=>'doctor not have activated course'], 404);
            
           }
    }  
    public function get_all_total()
    {
        $doctor_auth = Auth::User();
        $doctor = Doctor::find(Auth::User()->id);
        $total_all = [];
        foreach ($doctor->ActivatedCourses as $k) {
              
             $total_get = Total::where('activated_courses_id', '=',  $k->id)->get();
            if(sizeof($total_get)){
                 $total_all  [] =$total_get;
                }
            
        }
       
        $All_total_doctor=[];
        foreach ($total_all as $kk) {
 
            foreach ($kk as $k) {
                
                $total[] = ActivatedCourses::find($k->activated_courses_id)->course_code ;  
                $total[] = ($k->t_1 * 100) / ($k->count * 5);
                $total[] = ($k->t_2 * 100) / ($k->count * 5);
                $total[] = ($k->t_3 * 100) / ($k->count * 5);
                $total[] = ($k->t_4 * 100) / ($k->count * 5);
                $total[] = ($k->t_5 * 100) / ($k->count * 5);
                $total[] = ($k->t_6 * 100) / ($k->count * 5);
                $total[] = ($k->t_7 * 100) / ($k->count * 5);
                $total[] = ($k->t_8 * 100) / ($k->count * 5);
                $total[] = ($k->t_9 * 100) / ($k->count * 5);
                $total[] = ($k->t_10 * 100) / ($k->count * 5);
                $total[] = ($k->t_11 * 100) / ($k->count * 5);
                $total[] = ($k->t_12 * 100) / ($k->count * 5);
                $total[] = ($k->t_13 * 100) / ($k->count * 5);
                $total[] = ($k->t_14 * 100) / ($k->count * 5);
                $total[] = ($k->t_15 * 100) / ($k->count * 5);

                $All_total_doctor[] = $total;
            }
        }
        return  $All_total_doctor;
    } 
    public function  get_all_total_assistant()
    {
        $doctor_auth = Auth::User();
        $doctor = Doctor::find(Auth::User()->id);
        $total_all = [];
        foreach ($doctor->ActivatedCourses as $k) {

            $total_gets = TotalAssistants::where('activated_courses_id', '=',  $k->id)->get();
            if (sizeof($total_gets)) {
                $total_all[] = $total_gets;
            }
        }
        
        $All_total_assistant= [];
        foreach ($total_all as $kk) {

            foreach ($kk as $k) {
                 
                $total_a[] = ActivatedCourses::find($k->activated_courses_id)->course_code;
                $total_a[] = ($k->t_16 * 100) / ($k->count * 5);
                $total_a[] = ($k->t_17 * 100) / ($k->count * 5);
                $total_a[] = ($k->t_18 * 100) / ($k->count * 5);
                $total_a[] = ($k->t_19 * 100) / ($k->count * 5);
                $total_a[] = ($k->t_20 * 100) / ($k->count * 5);

                $All_total_assistant[] = $total_a;
            }
        }
        return  $All_total_assistant;
    }
 
}
 