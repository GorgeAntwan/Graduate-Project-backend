<?php

namespace App\Http\Controllers;

use App\ActivatedCourses;
use App\Assistant;
use App\Course;
use App\Department;
use App\Doctor;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\StudentResource;
use App\Total;
use App\TotalAssistants;
use Illuminate\Http\Request;
use App\Questionnaire;
use App\EmptyFiles;
class ApiDoctorController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:doctor-api');
    }

    public function check_admin()
    {   
        $all_id = [];
        $all_user = Department::all();
        foreach ($all_user as $k) {
            $all_id[]= $k->doctor_manager_id ;
            $all_id[]= $k->leader_of_duties_ofQuality;
        }
        
        if(!in_array(auth()->user()->id,$all_id)){
            return response()->json(['isAdmin'=>false],401);
        } else{
            return response()->json(['isAdmin'=>true],201);
        }
    }

    public function check_coordinator($id)
    {
        $activated_course =ActivatedCourses::find($id);
        if(auth()->user()->id!=$activated_course->coordinator_id){
           return "false";
        } else{
            return "true";
        }
    }

    public function testcheck($id)
    {
        return   EmptyFiles::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        # code...
    }
    public function currentuser()
    {
        try {

            $user =Doctor::findOrFail(auth()->user()->id);
            $user->teaching_hours =$this->computeTeachingHour($user->id);
            $user->save();
            return new DoctorResource($user);

        } catch (\Throwable $th) {
         
            return response()->json(['message'=>'this  id not found'],200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function get_comment($id)
    {      
        $activated_course =ActivatedCourses::find($id);
        if($activated_course!='[]'){
            $doctor=Doctor::find(auth()->user()->id);
            if($doctor->ActivatedCourses!='[]'){
               // $all_course_id =[];
               foreach($doctor->ActivatedCourses as $k){
                   $all_course_id [] = $k->id;
               } 
              
                if( in_array($id, $all_course_id)){
                  $Questionnaire= Questionnaire::Where('activated_courses_id','=',$id)->get();
                  foreach($Questionnaire as $comment){
                    if($comment->comment!=NULL){
                        $all_coumment []=$comment->comment;
                    }

                  }
                  if($all_coumment!='[]'){
                    
                    return response()->json( ['all_comment'=>$all_coumment],201);
                  }else{
                    return response()->json(['massage'=>'not found any comment for this course'], 200);
                  }
                 
                }else{ return response()->json(['error'=>'doctor not have this is cours'], 200);}

            } else{ return response()->json(['error'=>'not found any cours for current doctor'], 200);}

        }else{
            return response()->json(['error'=>'this course not found enter valid id'], 200);
        }
      
    }
    public function computeTeachingHour($id)
    {
        
        $doctor = Doctor::find($id);
        $activatecourse_all = [];
        foreach ($doctor->ActivatedCourses as $k) {
            $activatecourse = ActivatedCourses::find($k->id);
            $activatecourse_all[]= $activatecourse->Course->theoritical_hours;
        }
        return  array_sum($activatecourse_all);
    }
    public function Show_ActivatedCourse_theoritical()
    {
        try {
          
      
            $user =Doctor::findOrFail(auth()->user()->id)->ActivatedCourses()->get();
            if (collect($user)->isEmpty()) {
                return response()->json(['message'=>'not found any activated course for you'], 200);
            }else {
                foreach ($user as $k) {
                    
                    $ActivatedCourses_id []= $k->id;
                }
                $ActivatedCourses_id;
                $all =[];
                $u =Doctor::find(auth()->user()->id);
                foreach ($ActivatedCourses_id as $k) {
                    $s =[];
                    
                    $c= ActivatedCourses::findOrFail($k);
                    $coure= ActivatedCourses::findOrFail($k)->Course()->get();
                    foreach ($coure as $v) {
                        $coure_name = $v->name;
                        $theoritical_hours=$v->theoritical_hours;
                    }  
                    
                    $s= [
                    'Doctor_name' => $u->name,
                    'Doctor_id'=> $u->id,
                    'Activated_course_id' => $c->id,
                    'course_code' => $c->course_code,
                    'semester' => $c->semester,
                    'year' => $c->year,
                    'course_name' => $coure_name,
                    'theoritical_hours'=>$theoritical_hours, 
                    'isCoordinator'=>$this->check_coordinator($c->id),
                    ];
                    $all []=$s;
                       $s;
                }
                
                return DoctorResource::collection($all);
            }
        } catch (\Throwable $th) {
            return response()->json(['message'=>'your id not valid :('],200);
        }
         
    }
    public function get_all_total($id)
    {
       
       try {
            $doctor = Doctor::find(auth()->user()->id);
        } catch (\Throwable $th) {
            return response()->json(['message'=>'your id not valid :('],200);
        }
      
        $total_all = [];
        $All_course_id = [];
        foreach ($doctor->ActivatedCourses as $k) {
            $All_course_id[]=$k->id;

        }
       
        if(in_array($id, $All_course_id)){
            
            $total_get = Total::where('activated_courses_id', '=',$id)->get();
            if(sizeof($total_get)){
                foreach ($total_get as $k) {
                    if($k->doctor_id==$doctor->id){
                        $total_all[]=$k;
                        }
                }if(sizeof($total_all)){
                     
                    foreach ($total_all as $k) {
                       $cours_code= ActivatedCourses::find($k->activated_courses_id)->course_code;
                    $total_all =[
                        'doctor_name' => $doctor->name,
                        'course_code'=>  $cours_code,
                        'course_name' =>Course::find( $cours_code)->name,
                        't_1'=> (($k->t_1 * 100) / ($k->count * 5))/20,
                        't_2' => ($k->t_2 * 100) / ($k->count * 5)/20,
                        't_3' =>($k->t_3 * 100) / ($k->count * 5)/20,
                        't_4' => ($k->t_4 * 100) / ($k->count * 5)/20,
                        't_5' =>($k->t_5 * 100) / ($k->count * 5)/20,
                        't_6' =>($k->t_6 * 100) / ($k->count * 5)/20,
                        't_7' => ($k->t_7 * 100) / ($k->count * 5)/20,
                        't_8' =>($k->t_8 * 100) / ($k->count * 5)/20,
                        't_9' => ($k->t_9 * 100) / ($k->count * 5)/20,
                        't_10' => ($k->t_10 * 100) / ($k->count * 5)/20,
                        't_11' =>($k->t_11 * 100) / ($k->count * 5)/20,
                        't_12' => ($k->t_12 * 100) / ($k->count * 5)/20,
                        't_13' =>($k->t_13 * 100) / ($k->count * 5)/20,
                        't_14' => ($k->t_14 * 100) / ($k->count * 5)/20,
                        't_16' =>($k->t_16 * 100) / ($k->count * 5)/20,
                        't_17' =>($k->t_17 * 100) / ($k->count * 5)/20,
                        't_18' =>($k->t_18 * 100) / ($k->count * 5)/20,
                        't_19' => ($k->t_19 * 100) / ($k->count * 5)/20,
                        't_20' =>($k->t_20 * 100) / ($k->count * 5)/20,
                        
                    ]; 
                    }
                $total []=$total_all;
                    $total_practical= $this->get_all_total_practical($id);
                    if($total_practical!=""){
                        $total []=$total_practical->original;
                    } 
                       return   response()->json($total,201);
                       
                }else{return response()->json(['message'=>'not found total yet'],200);}
                    
            }else{return response()->json(['message'=>'not found total yet'],200);}
                
             
            

        }else{ return response()->json(['message'=>'you not allow to access this course :('],200);}
       
    }
    public function get_all_total_practical($id)
    {
       
        $all_total_practical =TotalAssistants::where('activated_courses_id','=',$id)->get();
        if($all_total_practical->isEmpty()){
            return response()->json(['message'=>'not found total practical for this course :('],200);
        }else{
            $all_assistant_id=[];
            foreach ($all_total_practical as $kkk) {
               $all_assistant_id[]=$kkk->assistant_id;
            }
   
             
         $all_totai_assistant =[];
            foreach ( $all_assistant_id as $ke) { 
                  $assistant = Assistant::find($ke);
               if($assistant==""){
                 return response()->json(['message'=>'not found id assistant for this is course :('],200);
               }
               $total_all = [];    
               $All_course_id = [];
               foreach ($assistant->ActivatedCourses as $k) {
                   $All_course_id[]=$k->id;
       
               }
   
               if(in_array($id, $All_course_id)){
               
                   $total_get = TotalAssistants::where('activated_courses_id', '=',$id)->get();
                   if(sizeof($total_get)){
                       foreach ($total_get as $k) {
                           if($k->assistant_id==$assistant->id){
                               $total_all[]=$k;
                               }
                       }if(sizeof($total_all)){
                            
                           foreach ($total_all as $k) {
                              $cours_code= ActivatedCourses::find($k->activated_courses_id)->course_code;
                           $total =[
                               'assistant_name' => $assistant->name,
                               'course_code'=>  $cours_code,
                               'course_name' =>Course::find($cours_code)->name,
                               't_15' => ($k->t_15 * 100) / ($k->count * 5)/20,
                               't_21'=> (($k->t_21 * 100) / ($k->count * 5))/20,
                               't_22' => ($k->t_22 * 100) / ($k->count * 5)/20,
                               't_23' =>($k->t_23 * 100) / ($k->count * 5)/20,
                               't_24' => ($k->t_24 * 100) / ($k->count * 5)/20,
                               't_25' =>($k->t_25 * 100) / ($k->count * 5)/20,
                               't_26' =>($k->t_26 * 100) / ($k->count * 5)/20,
                               't_27' => ($k->t_27 * 100) / ($k->count * 5)/20,
                               't_28' =>($k->t_28 * 100) / ($k->count * 5)/20,
                           ];
                           $all_totai_assistant []= $total;
                           }
   
       
                              
                       }else{return response()->json(['message'=>'not found total yet'],200);}
                           
                   }else{return response()->json(['message'=>'not found total yet'],200);}
                       
                    
                   
       
               }else{ return response()->json(['message'=>'you not allow to access this course :('],200);}
   
            }
   
            return   response()->json($all_totai_assistant,201);
   
   
   
         
          
        }
      
       

       
    }
}
