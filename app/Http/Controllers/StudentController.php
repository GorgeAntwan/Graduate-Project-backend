<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Config;
use App\Student;
use App\Questionnaire;
use App\ActivatedCourses;
use App\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use App\Total;
use App\TotalAssistants;

class StudentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:student');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */  
    public function index()
    {  //this function for test only can elte when only frontend 
      /***  $Student = Auth::User() ;  //this function used to get informtion about current authentication
        
        $course= array();         //this array is used to store all courses that student register can  evluateand the ttendence greter or equal 60
       
  
        foreach ($Student->ActivatedCourses as $s) {
             
            // to get all activated course that current student regester in this is course
              
            $activated_courses_id = $s->pivot->activated_courses_id;
           
            if ( $s->pivot->attendence >= 60 && $s->pivot->done_theoritical == 0) {// to check in attendence and check if not done evluate
                
                $course[] = $activated_courses_id;//and store the course that can evluate
            
            }
        }
        
        $all_information_curse =[];// this is array to get all information about the course
        foreach ($course as $k) {
            $currentcourse = ActivatedCourses::find($k);
            $all_information_curse[] = $currentcourse;
        }

        return view('student',compact('all_information_curse')); */
    }
    public function home()
    {
        return view('auth.student-home');
    }
    public function showEvlution(ActivatedCourses $id)
    {
       // echo $id;
        return view('student-evlution',compact('id'));
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'Answer_1' => ['required','min:1', 'max:1'],
            'Answer_2' => ['required', 'min:1', 'max:1'],
            'Answer_3' => ['required', 'min:1', 'max:1'],
            'Answer_4' => ['required', 'min:1', 'max:1'],
            'Answer_5' => ['required', 'min:1', 'max:1'],
            'Answer_6' => ['required', 'min:1', 'max:1'],
            'Answer_7' => ['required', 'min:1', 'max:1'],
            'Answer_8' => ['required', 'min:1', 'max:1'],
            'Answer_9' => ['required', 'min:1', 'max:1'],
            'Answer_10' => ['required', 'min:1', 'max:1'],
            'Answer_11' => ['required', 'min:1', 'max:1'],
            'Answer_12' => ['required', 'min:1', 'max:1'],
            'Answer_13' => ['required', 'min:1', 'max:1'],
            'Answer_14' => ['required', 'min:1', 'max:1'],
            
            'Answer_16' => ['required', 'min:1', 'max:1'],
            'Answer_17' => ['required', 'min:1', 'max:1'],
            'Answer_18' => ['required', 'min:1', 'max:1'],
            'Answer_19' => ['required', 'min:1', 'max:1'],
            'Answer_20' => ['required', 'min:1', 'max:1'],
            
        ]);
    }
    protected function validator_Partical(array $data)
    {
        return Validator::make($data, [

            'Answer_15' => ['required', 'min:1', 'max:1'],
            'Answer_21' => ['required', 'min:1', 'max:1'],
            'Answer_22' => ['required', 'min:1', 'max:1'],
            'Answer_23' => ['required', 'min:1', 'max:1'],
            'Answer_24' => ['required', 'min:1', 'max:1'],
            'Answer_25' => ['required', 'min:1', 'max:1'],
            'Answer_26' => ['required', 'min:1', 'max:1'],
            'Answer_27' => ['required', 'min:1', 'max:1'],
            'Answer_28' => ['required', 'min:1', 'max:1'],
        ]);
    }
    public function prosses(ActivatedCourses $id,Request $request)
    {
         
        $Student = Auth::User();  //this function used to get informtion about current authentication
        $this->validator($request->all())->validate();


        $store_id_course=[];// store all activated course id about student
        foreach ($Student->ActivatedCourses as $k) {
            $store_id_course[]= $k->pivot->activated_courses_id;
        }
        
        if (in_array($id, $store_id_course)) {// if student has this course start questonnare
            $activated_courses = ActivatedCourses::findOrFail($id->id);   //to get all informtion about the current course that can evluate
            foreach ($Student->ActivatedCourses as $s) {
              if ($s->pivot->activated_courses_id == $id->id) {//to check this course the student is activated
                if ($s->pivot->done_theoritical == 0 && $s->pivot->attendence >= 60) {//to check the evluate is not done and void to o evluate more than one
                   
                    // echo $doctorId ;
                   try {
                        foreach ($activated_courses->Doctor as $doctors) {    //to get the doctor that give the activated course

                            $doctorId = $doctors->id;
                        }
                   } catch (\Throwable $th) {
                       return response()->json(['message'=>'this  activated course not have for doctor'], 404);
                   }
                   try {
                       foreach ($activated_courses->Assistant as $ass) {
                           $assistantId = $ass->id;    //to get the current assistant that give this is course
                       }
                   } catch (\Throwable $th) {
                       return response()->json(['message'=>'this  activated course not have for assistant'], 404);
                   }
                   
                   //this used to create new questionnare
                   $questionareNew = Questionnaire::create([
                        'activated_courses_id' =>  $id->id,
                        'doctor_id' => $doctorId,
                        'assistant_id' => $assistantId,
                        'comment' => $request->comment
                    ]);

                   $questionareNew->Question()->attach(1, ['answer' => $request->Answer_1]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(2, ['answer' => $request->Answer_2]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(3, ['answer' => $request->Answer_3]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(4, ['answer' => $request->Answer_4]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(5, ['answer' => $request->Answer_5]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(6, ['answer' => $request->Answer_6]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(7, ['answer' => $request->Answer_7]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(8, ['answer' => $request->Answer_8]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(9, ['answer' => $request->Answer_9]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(10, ['answer' => $request->Answer_10]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(11, ['answer' => $request->Answer_11]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(12, ['answer' => $request->Answer_12]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(13, ['answer' => $request->Answer_13]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(14, ['answer' => $request->Answer_14]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(16, ['answer' => $request->Answer_16]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(17, ['answer' => $request->Answer_17]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(18, ['answer' => $request->Answer_18]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(19, ['answer' => $request->Answer_19]); // this function is used to insert new answer
                    $questionareNew->Question()->attach(20, ['answer' => $request->Answer_20]); // this function is used to insert new answer
                    
                    //after the Questionnare finsh seccessfully you must change nd update the attrbute done to 1
                   $s->pivot->done_theoritical = 1;
                   $s->pivot->save();
                   $total = ActivatedCourses::find($id->id)->Total;
                   if ($total) { //if total already exist in database make update only
                       $upte_total = Total::find($total->id);
                       $upte_total->t_1 = $upte_total->t_1 + $request->Answer_1;
                       $upte_total->t_2 = $upte_total->t_2 + $request->Answer_2;
                       $upte_total->t_3 = $upte_total->t_3 + $request->Answer_3;
                       $upte_total->t_4 = $upte_total->t_4 + $request->Answer_4;
                       $upte_total->t_5 = $upte_total->t_5 + $request->Answer_5;
                       $upte_total->t_6 = $upte_total->t_6 + $request->Answer_6;
                       $upte_total->t_7 = $upte_total->t_7 + $request->Answer_7;
                       $upte_total->t_8 = $upte_total->t_8 + $request->Answer_8;
                       $upte_total->t_9 = $upte_total->t_9 + $request->Answer_9;
                       $upte_total->t_10 = $upte_total->t_10 + $request->Answer_10;
                       $upte_total->t_11 = $upte_total->t_11 + $request->Answer_11;
                       $upte_total->t_12 = $upte_total->t_12 + $request->Answer_12;
                       $upte_total->t_13 = $upte_total->t_13 + $request->Answer_13;
                       $upte_total->t_14 = $upte_total->t_14 + $request->Answer_14;
                       $upte_total->t_16 = $upte_total->t_16 + $request->Answer_16;
                       $upte_total->t_17 = $upte_total->t_17 + $request->Answer_17;
                       $upte_total->t_18 = $upte_total->t_18 + $request->Answer_18;
                       $upte_total->t_19 = $upte_total->t_19 + $request->Answer_19;
                       $upte_total->t_20 = $upte_total->t_20 + $request->Answer_20;
                       
                       $upte_total->count = $upte_total->count + 1;
                       $upte_total->save();
                   } else { // if the total not found an not creat creat one
                       Total::create([
                            'activated_courses_id' =>  $id->id,
                            'count' => 1,
                            't_1' =>   $request->Answer_1,
                            't_2' =>   $request->Answer_2,
                            't_3' =>   $request->Answer_3,
                            't_4' =>   $request->Answer_4,
                            't_5' =>   $request->Answer_5,
                            't_6' =>   $request->Answer_6,
                            't_7' =>   $request->Answer_7,
                            't_8' =>   $request->Answer_8,
                            't_9' =>   $request->Answer_9,
                            't_10' =>  $request->Answer_10,
                            't_11' =>  $request->Answer_11,
                            't_12' =>  $request->Answer_12,
                            't_13' =>  $request->Answer_13,
                            't_14' =>  $request->Answer_14,
                            't_16' =>  $request->Answer_16,
                            't_17' =>  $request->Answer_17,
                            't_18' =>  $request->Answer_18,
                            't_19' =>  $request->Answer_19,
                            't_20' =>  $request->Answer_20,
                             
                        ]);
                   }
               } else {
                   return response()->json(['message'=>'the Questionnaire alerdy done'], 404);
               }
                }
            }
            // can delete when do frontend this for test only
            $check_Practical = ActivatedCourses::find($id->id);
            if ($check_Practical->Course->practical_hours) {
                return view('Practical', compact('id', 'questionareNew'));

            //return $questionareNew;//appled in frontend to get all iformation about new questionnare and get id about questionnre to can create parctical evluatation
            } else {
                return redirect()->route('student.dashboard');
            }
        }else{
            return response()->json(['message'=>'you not have this activated course'], 404);
        }

    }
    public function practicalEvlution(ActivatedCourses $id,Questionnaire $Q_id, Request $request)
    {
        $Student = Auth::User();
        $this->validator_Partical($request->all())->validate();
        // echo  $Q_id->id;
        $check_Practical = ActivatedCourses::find($id->id);
        if ($check_Practical->Course->practical_hours) {//to check this course have practical_hours if have do evluate
            foreach ($Student->ActivatedCourses as $s) {

                if ($s->pivot->activated_courses_id == $id->id) { //to check this course the student activate it
                    if ($s->pivot->done_practical == 0 && $s->pivot->attendence >= 60) { //to check the student not evluate this course to void evluate agin

                        $questionareNew = Questionnaire::find($Q_id->id);
                        $questionareNew->Question()->attach(15, ['answer' => $request->Answer_15]); // this function is used to insert new answer 
                        $questionareNew->Question()->attach(21, ['answer' => $request->Answer_21]); // this function is used to insert new answer 
                        $questionareNew->Question()->attach(22, ['answer' => $request->Answer_22]); // this function is used to insert new answer 
                        $questionareNew->Question()->attach(23, ['answer' => $request->Answer_23]); // this function is used to insert new answer 
                        $questionareNew->Question()->attach(24, ['answer' => $request->Answer_24]); // this function is used to insert new answer 
                        $questionareNew->Question()->attach(25, ['answer' => $request->Answer_25]); // this function is used to insert new answer 
                        $questionareNew->Question()->attach(26, ['answer' => $request->Answer_26]); // this function is used to insert new answer 
                        $questionareNew->Question()->attach(27, ['answer' => $request->Answer_27]); // this function is used to insert new answer 
                        $questionareNew->Question()->attach(28, ['answer' => $request->Answer_28]); // this function is used to insert new answer 
                        
                        $total = ActivatedCourses::find($id->id)->TotalAssistants;

                        if ($total) { //if total already exist in database make update only

                            $upte_total = TotalAssistants::find($total->id);
                            $upte_total->t_15 = $upte_total->t_15 + $request->Answer_15;
                            $upte_total->t_21 = $upte_total->t_21 + $request->Answer_21;
                            $upte_total->t_22 = $upte_total->t_22 + $request->Answer_22;
                            $upte_total->t_23 = $upte_total->t_23 + $request->Answer_23;
                            $upte_total->t_24 = $upte_total->t_24 + $request->Answer_24;
                            $upte_total->t_25 = $upte_total->t_25 + $request->Answer_25;
                            $upte_total->t_26 = $upte_total->t_26 + $request->Answer_26;
                            $upte_total->t_27 = $upte_total->t_27 + $request->Answer_27;
                            $upte_total->t_28 = $upte_total->t_28 + $request->Answer_28;
                            $upte_total->count = $upte_total->count + 1;
                            $upte_total->save();
                        } else { // if the total not found an not creat creat one

                            TotalAssistants::create([
                                'activated_courses_id' => $id->id,
                                'count' => 1,
                                't_15' =>  $request->Answer_15,
                                't_21' =>  $request->Answer_21,
                                't_22' =>  $request->Answer_22,
                                't_23' =>  $request->Answer_23,
                                't_24' =>  $request->Answer_24,
                                't_25' =>  $request->Answer_25,
                                't_26' =>  $request->Answer_26,
                                't_27' =>  $request->Answer_27,
                                't_28' =>  $request->Answer_28,

                            ]);
                        }
                        $s->pivot->done_practical = 1;
                        $s->pivot->save();
                    } else {
                        return response()->json(['message'=>'this course alredy done questionnire or your attendence is less than 60%'], 404);
                    }
                }else {
                    return response()->json(['message'=>'this is course not activated for you'], 404);
                }
            }
       
        } else {
            return response()->json(['message'=>'this course not have practical'], 404);
        }
       
        return response()->json(['message'=>'success'], 201);
    }
     public function get_all_information_about_student()
    {
    
        
       return response()->json( Student::find(Auth::User()->id), 201);
    } 
    public function get_all_activatedcourse()
    {

        try{
       
            $student = Student::find(Auth::User()->id);
            return response()->json($student->ActivatedCourses()->get(), 201);
        }catch (\Throwable $th) {
            return response()->json(['message'=>'student not have activated course'], 404);
            
        }
        
    }
    public function get_all_activted_course_theoritical_not_finsh()
    {
        $activted_course_theoritical_not_finsh =[];
        try {
          $activatedCourse = Student::findOrFail(Auth::User()->id)->ActivatedCourses;
     
          if (!is_null($activatedCourse)) {
              foreach ($activatedCourse as $k) {
                  if ($k->pivot->done_theoritical !=1  && $k->pivot->attendence >= 60) {
                      $activted_course_theoritical_not_finsh[] = $k;
                  }
              }
              return response()->json($activted_course_theoritical_not_finsh, 201);
          } else {
              return response()->json(['message'=>'record not found'], 404);
          }
        } catch (\Throwable $th) {
          return response()->json(['message'=>'record not found'], 404);
        }
    }
    public function get_all_activted_course_practical_not_finsh()
    {
        
        $activted_course_practical_not_finsh = [];
        try {
            $activatedCourse = Student::findOrFail(Auth::User()->id)->ActivatedCourses;
       
            if (!is_null($activatedCourse)) {
                foreach ($activatedCourse as $k) {
                    if ($k->pivot->done_practical != 1 && $k->pivot->attendence >= 60) {
                        $check_Practical = ActivatedCourses::find($k->pivot->activated_courses_id);
                        if ($check_Practical->Course->practical_hours) {//to check the course have practical_hours
                               $activted_course_practical_not_finsh[] = $k;
                        }  
        
                    }
                }
                return response()->json($activted_course_practical_not_finsh, 201);
            } else {
                return response()->json(['message'=>'record not found'], 404);
            }
          } catch (\Throwable $th) {
            return response()->json(['message'=>'record not found'], 404);
          }
        
    }
    public function get_all_course_not_allow_evluate()
    {
        $activted_course_not_allow_evluate = [];
    try {
        $activatedCourse = Student::findOrFail(Auth::User()->id)->ActivatedCourses;
   
        if (!is_null($activatedCourse)) {
            foreach ($activatedCourse as $k) {
                if ( $k->pivot->attendence <60) {
                    $activted_course_not_allow_evluate[] = $k;
                }
            }
            if (empty($activted_course_not_allow_evluate)) {
               return response()->json(['message'=>'all course activated for you can evluate'], 201);
   
           }else{ return response()->json($activted_course_not_allow_evluate, 201);}
   
           
        } else {
            return response()->json(['message'=>'record not found'], 404);
        }
               } catch (\Throwable $th) {
        return response()->json(['message'=>'record not found'], 404);
      }
    }
     
   public function get_all_question()
   {
    try  {
         
         
        return response()->json(Question::all(), 201);
       } catch (\Throwable $th) {
        return response()->json(['message'=>'record not found'], 404);
        
       }
   }
}
