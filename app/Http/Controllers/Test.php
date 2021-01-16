<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\ActivatedCourses;
use App\Course;
use App\Questionnaire;
use App\Question;
use App\Doctor;
use App\DoctorDuties;
use App\Department;
use App\Assistant;
use App\SkillsAssistant;
use App\Program;
use App\File;
use App\FileMissing;
use App\ILOs;
use App\form16;
use App\Total;
use App\TotalAssistants;
use App\ActivatedCoursesHasDoctor;
use App\AssistantHasActivatedCourses;
use App\ActivatedCoursesStudents;
use App\Http\Resources\StudentResource;
use Illuminate\Support\Collection;
class Test extends Controller
{
    public function creat()
    {
        /*
       // to get manger of  doctor
        $user = Doctor::find(5);
        //echo $user->Department->doctor_manager_id;
        $Manger = Doctor::find($user->Department->doctor_manager_id);
        echo  $Manger;

       */
        /** $user = form16::all();
       echo $user; */
        /** //to get total of each question of doctor
       $total = Doctor::find(2);
        foreach ($total->ActivatedCourses as $k) {
            echo'[';echo Total::findorfail($k->pivot->total_id);echo']';
        } */

        /** $to = Program::find(1);
     // echo $to->ActivatedCoursesStudents;

        foreach ($to->ActivatedCoursesStudents as $k) {
           echo $k->Student->id;echo',';
        } */

        /** $u =   ActivatedCourses::find(1)->Total;
        if($u){
            $t = Total::find($u->id);
            $t->t_1 = $t->t_1+5 ;
            $t->save();
            echo $t;

        } */
        /**$ActivatedCourses_id=[];
        $ActivatedCourses_code=[];
        $doctor =Doctor::find(2) ;
        foreach ($doctor->ActivatedCourses as $k) {
           $ActivatedCourses_id  []= $k->id;
           $ActivatedCourses_code []= $k->course_code;
        }
        $course_all = [] ;
         $course_assistant=[];
        foreach ($ActivatedCourses_id as $k ) {
           $course  = Total::where('activated_courses_id','=', $k)->get();
           $course_assistant  = TotalAssistants::where('activated_courses_id', '=', $k)->get();
        $course_all_doctor[] = $course;
        $course_all_assistant[] = $course_assistant;
        }

        $total = [];
        $All_total = [];
        foreach ($course_all_doctor as $kk ) {

          foreach ($kk as $k) {
           // $total[] = ;
           //$total[] = $ActivatedCourses_code[$k] ;
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


        foreach ($course_all_assistant as $kk ) {

          foreach ($kk as $k) {
           // $total[] = ;
           //$total[] = $ActivatedCourses_code[$k] ;
           $total[] = ($k->t_16 * 100) / ($k->count * 5);
           $total[] = ($k->t_17 * 100) / ($k->count * 5);
           $total[] = ($k->t_18 * 100) / ($k->count * 5);
           $total[] = ($k->t_19 * 100) / ($k->count * 5);
           $total[] = ($k->t_20 * 100) / ($k->count * 5);
           $All_total_assistant[] = $total;
          }}
        foreach ($ActivatedCourses_code as $k) {
          echo $k;
        }

        foreach  ($All_total_doctor as $k) {
         //echo  $ActivatedCourses_code[1] ;echo '>>>>';
          foreach ($k as $kk) {
          echo $kk;echo'% ,';
          }
        }
      foreach ($All_total_assistant as $k) {
        //echo  $ActivatedCourses_code[1] ;echo '>>>>';
        foreach ($k as $kk) {
           echo $kk;
           echo '% ,';
        }
      }
        foreach ($course_all_assistant as $k ) {
         echo $k;
        }
    } */

       
    }
   
}
