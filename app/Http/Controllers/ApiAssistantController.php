<?php

namespace App\Http\Controllers;

use App\ActivatedCourses;
use App\Assistant;
use App\Course;
use App\Http\Resources\AssistantResource;
use App\TotalAssistants;
use Illuminate\Http\Request;

class ApiAssistantController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:assistant-api'); 
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
         
    }

    public function getDescription($activate_course_id){
        try{
            $course_code = ActivatedCourses::find($activate_course_id)->course_code;
          }catch (\Throwable $th) {
              return response()->json(['error'=>'not found Activated Course Enter valid activated course id  '],200);
          }
          try{
             $description= Course::Find($course_code)->description;
             return response()->json(['description'=> $description],201);
           
          }catch (\Throwable $th) {
              return response()->json(['error'=>'not found Course'],200);
          } 
    }
    public function currentuser()
    {   
       try {
             return auth()->user();
             $user =Assistant::findOrFail(auth()->user()->id);
            $user->teaching_hours =$this->computeTeachingHour($user->id);
            $user->save();
            return new AssistantResource($user);

        } catch (\Throwable $th) {
         
            return response()->json(['message'=>'this  id not found'],200);
        } 
    }
    public function computeTeachingHour($id)
    {
        
        $assistant = Assistant::find($id);
        $activatecourse_all = [];
        foreach ($assistant->ActivatedCourses as $k) {
            $activatecourse = ActivatedCourses::find($k->id);
            $activatecourse_all[]= $activatecourse->Course->theoritical_hours;
        }
        return  array_sum($activatecourse_all);
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
    public function get_all_total_practical($id)
    {
       
       try {
            $assistant = Assistant::find(auth()->user()->id);
        } catch (\Throwable $th) {
            return response()->json(['message'=>'your id not valid :('],200);
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
                    }

                       return   response()->json($total,201);
                }else{return response()->json(['message'=>'not found total yet'],200);}
                    
            }else{return response()->json(['message'=>'not found total yet'],200);}
                
             
            

        }else{ return response()->json(['message'=>'you not allow to access this course :('],200);}
       
    }


   


    public function Show_ActivatedCourse_practical()
    {
        try {
          
      
            $user =Assistant::findOrFail(auth()->user()->id)->ActivatedCourses()->get();
            if (collect($user)->isEmpty()) {
                return response()->json(['message'=>'not found any activated course for you'], 200);
            }else {
                foreach ($user as $k) {
                    
                    $ActivatedCourses_id []= $k->id;
                }
                $ActivatedCourses_id;
                $all =[];
                $u =Assistant::find(auth()->user()->id);
                foreach ($ActivatedCourses_id as $k) {
                    $s =[];
                    
                    $c= ActivatedCourses::findOrFail($k);
                    $coure= ActivatedCourses::findOrFail($k)->Course()->get();
                    foreach ($coure as $v) {
                        $coure_name = $v->name;
                        $practical_hours=$v->practical_hours;
                    }  
                    $s= [
                    'assistant_name' => $u->name,
                    'assistant_id'=> $u->id,
                    'Activated_course_id' => $c->id,
                    'course_code' => $c->course_code,
                    'semester' => $c->semester,
                    'year' => $c->year,
                    'course_name' => $coure_name,
                    'practical_hours'=>$practical_hours, 
                    ];
                    $all []=$s;
                       $s;
                }
                
                return AssistantResource::collection($all);
            }
        } catch (\Throwable $th) {
            return response()->json(['message'=>'your id not valid :('],200);
        }
         
    }
}
