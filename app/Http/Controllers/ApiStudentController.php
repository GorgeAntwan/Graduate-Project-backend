<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Student;
use Illuminate\Http\Request;
use App\ActivatedCourses;
use App\Assistant;
use App\Doctor;
use App\Question;
use App\Questionnaire;
use App\Total;
use App\TotalAssistants;
use Illuminate\Support\Facades\Validator;
use App\File;
use Illuminate\Support\Facades\Storage;
use Response;
class ApiStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:student-api', ['except' => ['login','register']]);
    }
    
    
    public function getMissingFileExam($activated_courses_id)
    {   
        $activated_course = ActivatedCourses::find($activated_courses_id);
        if( $activated_course==""){
            return response()->json(['error'=>'not found Activated Courses enter valid id'], 200);
        }else{
            $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
            if($all_file =='[]'){
                
                $exam ='true';
                

            }else{
                foreach( $all_file as $k){
                    $file_id = $k->id;
                }
                  $exist_file=File::find( $file_id);
                
                //return form_11a::Where('file_id','=',$file_id)->get();
                
                if($exist_file->exam_file_path==NULL){
                    $exam ='true';
                }else{
                    $exam ='false';
                }
                
            }
        }
       
       /**if(in_array(false,$missFile)){
        return "false";
        } */
       return   $exam;
    } 





    public function getAllCode($activated_courses_id)
    {   
        if(ActivatedCourses::find($activated_courses_id)==""){

           return response()->json(['error'=>'not found this Activated course   :('],200);

        }
        $student = Student::find(auth()->user()->id);
        foreach($student->ActivatedCourses as $k){
            $all_activated_courses_id []=$k->id;

        }
        if(in_array($activated_courses_id,$all_activated_courses_id)){
        
            $course_code =ActivatedCourses::find($activated_courses_id)->course_code ;
            $allActivatedCourse = ActivatedCourses::Where('course_code','=', $course_code)->get();
             $allCoursesHasExam = [];
              foreach ( $allActivatedCourse as $k){
                  
                  if($this->getMissingFileExam($k->id)=='false'){
                    $allCoursesHasExam []=  $k;
                  }  
              }
            return response()->json( $allCoursesHasExam,201);

        }else{
            return response()->json(['error'=>'you not have this is course :('],200);
            
        }
        
        
    }

    public function downloadExamFile($activated_courses_id)
    {   
        if(ActivatedCourses::find($activated_courses_id)==""){

           return response()->json(['error'=>'not found this Activated course   :('],200);

        }
        /**$student = Student::find(auth()->user()->id);
        foreach($student->ActivatedCourses as $k){
            $all_activated_courses_id []=$k->id;

        } */
        //try{
            $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
            if($all_file =='[]'){
            return response()->json(['error'=>'not found exam for this Activated course   :('],200);
            }else{
            foreach( $all_file as $k){
                $file_id = $k->id;
            }
            $file=File::find($file_id);
            if($file->exam_file_path ==NULL){
                return response()->json(['error'=>'not found exam for this course '], 200);
            }
            $name= $file->exam_original_filename;
           // return  Response::download(public_path($file->exam_file_path), $name);
           
                ob_end_clean();
                return response()->download(storage_path('app/public/'.$file->exam_file_path),$name);
                //return Storage::download($file->exam_file_path,$name);
            }
        /*}catch (\Throwable $th) {
            return response()->json(['error'=>'not found exam file '], 200);
        } */
        
        
    }

    




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
    public function show($id)
    {   
        try { 

            $user =Student::findOrFail($id);
            
            return new StudentResource($user);

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
    
    public function Show_ActivatedCourse_theoritical()
    {     
       try {
        
        $user =Student::findOrFail(auth()->user()->id)->ActivatedCourses()->get();
        if( collect($user)->isEmpty()){

            return response()->json(['message'=>'not found any activated course for you'],200);

        }else{ 
            foreach ($user as $k  ) {
            
            //$ActivatedCourses_id []= $k->id;
            $couress= ActivatedCourses::findOrFail($k->id)->Course()->get();
            if($couress!="[]"){
               $ActivatedCourses_id []= $k->id;
               }
            }
            $all =[];
           // return  $ActivatedCourses_id ;
            foreach ($ActivatedCourses_id as $k) {
                $s =[];
                foreach ($user as $u) {
                    if ($k ==$u->pivot->activated_courses_id) {
                        $done_theoritical=$u->pivot->done_theoritical;
                        $done_theoritical_2=$u->pivot->done_theoritical_2;
                        $done_theoritical_3=$u->pivot->done_theoritical_3;
                         $attendence=$u->pivot->attendence;
                    }
                
                
                    $c= ActivatedCourses::findOrFail($k);
                    $coure= ActivatedCourses::findOrFail($k)->Course()->get();
                    foreach ($coure as $v) {
                        $coure_name = $v->name;
                        $theoritical_hours=$v->theoritical_hours;
                    }
                }
                $ss= ActivatedCourses::findOrFail($k)->Doctor()->get();
              
                foreach ($ss as $kk) {
                    $Doctor_name = $kk->name;
                    $Doctor_id = $kk->id;
                    if($Doctor_id==$done_theoritical||$Doctor_id==$done_theoritical_2||$Doctor_id==$done_theoritical_3){
                        $s= [
                            'Activated_course_id' => $c->id,
                            'course_code' => $c->course_code,
                            'semester' => $c->semester,
                            'year' => $c->year,
                            'done_theoritical'=>1,
                            'attendence'=>$attendence,
                            'course_name' => $coure_name,
                            'theoritical_hours'=>$theoritical_hours,
                            'Doctor_name' => $Doctor_name,
                            'Doctor_id'=> $Doctor_id,
                           ];
                    }else{
                        $s= [
                            'Activated_course_id' => $c->id,
                            'course_code' => $c->course_code,
                            'semester' => $c->semester,
                            'year' => $c->year,
                            'done_theoritical'=>0,
                            'attendence'=>$attendence,
                            'course_name' => $coure_name,
                            'theoritical_hours'=>$theoritical_hours,
                            'Doctor_name' => $Doctor_name,
                            'Doctor_id'=> $Doctor_id,
                           ];
                    }
                    
                    $all []=$s;
                }
            }
            return StudentResource::collection($all);
            }
            } catch (\Throwable $th) {
                return response()->json(['message'=>'your id not valid :('],200);
            }
    }
        public function Show_ActivatedCourse_practical()
        { 
          try {
            $user =Student::findOrFail(auth()->user()->id)->ActivatedCourses()->get();
            if( collect($user)->isEmpty()){
    
                return response()->json(['message'=>'not found any activated course for you'],200);
    
            }else{ foreach ($user as $k  ) {
              
                $ActivatedCourses_id []= $k->id;
              }
              $all =[];
             
                foreach ( $ActivatedCourses_id as $k) {
                  $s =[];
                  foreach ($user as $u) {
                    if($k ==$u->pivot->activated_courses_id)
                    {
                        $done_practical=$u->pivot->done_practical;
                        $done_practical_2=$u->pivot->done_practical_2;
                        $done_practical_3=$u->pivot->done_practical_3;
                        $attendence=$u->pivot->attendence;
                    }
                   
                    
                  }
                   
                  $c= ActivatedCourses::findOrFail($k);
                  $coure= ActivatedCourses::findOrFail($k)->Course()->get();
                  foreach ($coure as $v ) {
                    $coure_name = $v->name;
                    
                    $practical_hours=$v->practical_hours;
                    if($practical_hours>0){
                        if(collect($c->Assistant()->get())->isEmpty()){
                            $assistant_name='not found assistant yet for this course';
                        }else{
                            foreach ($c->Assistant()->get() as $ass) {
                             $assistant_name= $ass->name;
                             $assistant_id= $ass->id;
                             if($assistant_id==$done_practical||$assistant_id==$done_practical_2||$assistant_id==$done_practical_3){
                                $s= [
                                    'Activated_course_id' => $c->id,
                                    'course_code' => $c->course_code,
                                    'semester' => $c->semester,
                                    'year' => $c->year,
                                    'done_practical'=> 1,
                                    'attendence'=>$attendence,
                                    'course_name' => $coure_name,
                                    'practical_hours' =>$practical_hours ,
                                    'assistant_name'=>$assistant_name,
                                    'assistant_id'=> $assistant_id,
                                   
                                   ];
                             }else{
                                $s= [
                                    'Activated_course_id' => $c->id,
                                    'course_code' => $c->course_code,
                                    'semester' => $c->semester,
                                    'year' => $c->year,
                                    'done_practical'=> 0,
                                    'attendence'=>$attendence,
                                    'course_name' => $coure_name,
                                    'practical_hours' =>$practical_hours ,
                                    'assistant_name'=>$assistant_name,
                                    'assistant_id'=> $assistant_id,
                                   
                                   ];
                             }
                           
                               $all []=$s;
                            } 
                        }
                       
                    }
                  }
                 
                  
                  
                  
                 
                }
                return StudentResource::collection($all);
                }
                } catch (\Throwable $th) {
                    return response()->json(['message'=>'your id not valid :('],200);
                }
            }
        public function test(ActivatedCourses $id )
        {   
            $doctor=2;  
            $total = ActivatedCourses::find($id->id)->Total;
           foreach ( $total  as $k) {
               $doctor_total_id []= $k->doctor_id;
           }
           if(in_array($doctor,$doctor_total_id)){
                
            $All_tototal =  Total::Where('doctor_id','=',$doctor)->get();
            foreach ($All_tototal as $k) {
                if($k->activated_courses_id==$id->id){
                    $total_new =$k;
                }
            }
            if($total_new){
                $t =Total::find($total_new->id);
            }
            return $t;
           }
           return 'not found';
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

        public function prosses(ActivatedCourses $id ,Doctor$doctor,Request $request)
        {     
           try { 

            $store_id_course_doctor=[];// store all activated course id about student
            foreach ($doctor->ActivatedCourses as $k) {
                $store_id_course_doctor[]= $k->pivot->activated_courses_id;
            }
            if (in_array($id->id, $store_id_course_doctor)) {
                 $doctorId =  $doctor->id;
            }else{ return response()->json(['message' =>'this doctor not give this course'], 200);}
           } catch (\Throwable $th) {
             return response()->json(['message' =>'not valid doctor id'], 200);
           }
           
            //remove this comment when make auth
            //$Student = Auth::User();  //this function used to get informtion about current authentication
            $Student = Student::findOrFail(auth()->user()->id);
             try {
                $this->validator($request->all())->validate();
             } catch (\Throwable $th) {
                return response()->json($this->validator($request->all())->errors(), 200);
             }


            $store_id_course=[];// store all activated course id about student
            foreach ($Student->ActivatedCourses as $k) {
                $store_id_course[]= $k->pivot->activated_courses_id;
            }
             
            if (in_array($id->id, $store_id_course)) {// if student has this course start questonnare
                $activated_courses = ActivatedCourses::findOrFail($id->id);   //to get all informtion about the current course that can evluate
                foreach ($Student->ActivatedCourses as $s) {
                if ($s->pivot->activated_courses_id == $id->id) {//to check this course the student is activated
                    if ($s->pivot->done_theoritical == 0 &&$s->pivot->done_theoritical_2 != $doctor->id &&$s->pivot->done_theoritical_3 != $doctor->id ) {//to check the evluate is not done and void to o evluate more than one
                          
                      if( $s->pivot->attendence >= 60){
                    
                 
                    
                      //this used to create new questionnare
                        $questionareNew = Questionnaire::create([
                            'activated_courses_id' =>  $id->id,
                            
                        ]);
                        if($request->comment){
                            $qu = Questionnaire::find($questionareNew->id);
                            $qu->comment = $request->comment;
                            $qu->save();
                            
                        }
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
                        $s->pivot->done_theoritical = $doctor->id;
                        $s->pivot->save();
                        $total = ActivatedCourses::find($id->id)->Total;
            
                        if ($total) { //if total already exist in database make update only
                            $doctor_total_id =[];
                            foreach ( $total  as $k) {
                                $doctor_total_id []= $k->doctor_id;
                            }
                            if(in_array($doctorId,$doctor_total_id)){
                                $total_new="";
                                    $All_tototal =  Total::Where('doctor_id','=',$doctorId)->get();
                                foreach ($All_tototal as $k) {
                                    if($k->activated_courses_id==$id->id){
                                            $total_new =$k;
                                    }
                                }
                    
                            if($total_new!=""){
                                $upte_total = Total::find($total_new->id);
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
                            }

                        }else{
                            Total::create([
                                'activated_courses_id' =>  $id->id,
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
                              
                                
                                'count' => 1,
                                'doctor_id' => $doctorId,
                            ]);

                            }
                            
                        } else { // if the total not found an not creat creat one
                            Total::create([
                                    'activated_courses_id' =>  $id->id,
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
                                    
                                    'count' => 1,
                                    'doctor_id' => $doctorId,
                                ]);
                        }
                        }else{return response()->json(['message'=>'your attendence less than 60%'], 401);}

                    }
                    elseif(($s->pivot->done_theoritical_2 == 0 &&$s->pivot->done_theoritical != $doctor->id &&$s->pivot->done_theoritical_3 != $doctor->id  )){
                        //to check the evluate is not done and void to o evluate more than one
                          
                      if( $s->pivot->attendence >= 60){
                    
                 
                    
                        //this used to create new questionnare
                          $questionareNew = Questionnaire::create([
                              'activated_courses_id' =>  $id->id,
                              
                          ]);
                          if($request->comment){
                              $qu = Questionnaire::find($questionareNew->id);
                              $qu->comment = $request->comment;
                              $qu->save();
                              
                          }
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
                          $s->pivot->done_theoritical_2 = $doctor->id;
                          $s->pivot->save();
                          $total = ActivatedCourses::find($id->id)->Total;
              
                          if ($total) { //if total already exist in database make update only
                              $doctor_total_id =[];
                              foreach ( $total  as $k) {
                                  $doctor_total_id []= $k->doctor_id;
                              }
                              if(in_array($doctorId,$doctor_total_id)){
                                  $total_new="";
                                      $All_tototal =  Total::Where('doctor_id','=',$doctorId)->get();
                                  foreach ($All_tototal as $k) {
                                      if($k->activated_courses_id==$id->id){
                                              $total_new =$k;
                                      }
                                  }
                      
                              if($total_new!=""){
                                  $upte_total = Total::find($total_new->id);
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
                              }
  
                          }else{
                              Total::create([
                                  'activated_courses_id' =>  $id->id,
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
                                  
                                  
                                  'count' => 1,
                                  'doctor_id' => $doctorId,
                              ]);
  
                              }
                              
                          } else { // if the total not found an not creat creat one
                              Total::create([
                                      'activated_courses_id' =>  $id->id,
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
                                      
                                      'count' => 1,
                                      'doctor_id' => $doctorId,
                                  ]);
                          }
                          }else{return response()->json(['message'=>'your attendence less than 60%'], 401);}
  
  

                    }
                    elseif(($s->pivot->done_theoritical_3 == 0 &&$s->pivot->done_theoritical != $doctor->id &&$s->pivot->done_theoritical_2 != $doctor->id  )){
                        //to check the evluate is not done and void to o evluate more than one
                          
                      if( $s->pivot->attendence >= 60){
                    
                 
                    
                        //this used to create new questionnare
                          $questionareNew = Questionnaire::create([
                              'activated_courses_id' =>  $id->id,
                              
                          ]);
                          if($request->comment){
                              $qu = Questionnaire::find($questionareNew->id);
                              $qu->comment = $request->comment;
                              $qu->save();
                              
                          }
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
                          $s->pivot->done_theoritical_3 = $doctor->id;
                          $s->pivot->save();
                          $total = ActivatedCourses::find($id->id)->Total;
              
                          if ($total) { //if total already exist in database make update only
                              $doctor_total_id =[];
                              foreach ( $total  as $k) {
                                  $doctor_total_id []= $k->doctor_id;
                              }
                              if(in_array($doctorId,$doctor_total_id)){
                                  $total_new="";
                                      $All_tototal =  Total::Where('doctor_id','=',$doctorId)->get();
                                  foreach ($All_tototal as $k) {
                                      if($k->activated_courses_id==$id->id){
                                              $total_new =$k;
                                      }
                                  }
                      
                              if($total_new!=""){
                                  $upte_total = Total::find($total_new->id);
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
                              }
  
                          }else{
                              Total::create([
                                  'activated_courses_id' =>  $id->id,
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
                                   
                                  
                                  'count' => 1,
                                  'doctor_id' => $doctorId,
                              ]);
  
                              }
                              
                          } else { // if the total not found an not creat creat one
                              Total::create([
                                      'activated_courses_id' =>  $id->id,
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
                                      
                                      'count' => 1,
                                      'doctor_id' => $doctorId,
                                  ]);
                          }
                          }else{return response()->json(['message'=>'your attendence less than 60%'], 401);}
  
  

                    }
                    else {
                            return response()->json(['message'=>'the Questionnaire alerdy done'], 200);
                    }
                 }
                }
                
            }else{
                return response()->json(['message'=>'you not have this activated course'], 200);
            }
            return response()->json(['message'=>' success thanks you for evluate'], 201);
        }
        public function practicalEvlution(ActivatedCourses $id ,Assistant $assistant, Request $request)
        {
            //$Student = Auth::User();
            
            // echo  $Q_id->id;
             
            try {

                $store_id_course_assistant=[];// store all activated course id about student
                foreach ($assistant->ActivatedCourses as $k) {
                    $store_id_course_assistant[]= $k->pivot->activated_courses_id;
                }
                if (in_array($id->id, $store_id_course_assistant)) {
                     $assistantId =  $assistant->id;
                }else{ return response()->json(['message' =>'this assistant not give this course'], 200);}
               } catch (\Throwable $th) {
                 return response()->json(['message' =>'not valid assistant id'], 200);
               }
               
                //remove this comment when make auth
                //$Student = Auth::User();  //this function used to get informtion about current authentication
                $Student = Student::findOrFail(auth()->user()->id);
                 try {
                    $this->validator_Partical($request->all())->validate();
                 } catch (\Throwable $th) {
                    return response()->json($this->validator_Partical($request->all())->errors(), 200);
                 }
    
            $check_Practical = ActivatedCourses::find($id->id);
            if ($check_Practical->Course->practical_hours) {//to check this course have practical_hours if have do evluate
                $store_id_course =[];
                foreach ($Student->ActivatedCourses as $s) {
                     
                        $store_id_course []=$s->pivot->activated_courses_id;
                        
                   
                }
                if (in_array($id->id, $store_id_course)) {// if student has this course start questonnar
                    $activated_courses = ActivatedCourses::findOrFail($id->id);   //to get all informtion about the current course that can evluate
                    foreach ($Student->ActivatedCourses as $s) {
                        if ($s->pivot->activated_courses_id == $id->id) {
                            if ($s->pivot->done_practical == 0 && $s->pivot->attendence >= 60&&$s->pivot->done_practical_2 !=  $assistant->id&&$s->pivot->done_practical_3 !=  $assistant->id) { //to check the student not evluate this course to void evluate agin

                                $questionareNew = Questionnaire::create([
                                'activated_courses_id' =>  $id->id,
                                
                                ]);
                                if($request->comment){
                                    $qu = Questionnaire::find($questionareNew->id);
                                    $qu->comment = $request->comment;
                                    $qu->save();
                                    
                                }
                                $questionareNew->Question()->attach(15, ['answer' => $request->Answer_15]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(21, ['answer' => $request->Answer_21]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(22, ['answer' => $request->Answer_22]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(23, ['answer' => $request->Answer_23]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(24, ['answer' => $request->Answer_24]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(25, ['answer' => $request->Answer_25]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(26, ['answer' => $request->Answer_26]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(27, ['answer' => $request->Answer_27]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(28, ['answer' => $request->Answer_28]); // this function is used to insert new answer
                                $s->pivot->done_practical =  $assistant->id;
                                $s->pivot->save();
                                $total = ActivatedCourses::find($id->id)->TotalAssistants;
                                if ($total) { //if total already exist in database make update only
                                    $assistant_total_id =[];
                                    foreach ($total  as $k) {
                                        $assistant_total_id []= $k->assistant_id;
                                    }
                                    if (in_array($assistantId, $assistant_total_id)) {
                                        $total_new="";
                                        $All_tototal =  TotalAssistants::Where('assistant_id', '=', $assistantId)->get();
                                        foreach ($All_tototal as $k) {
                                            if ($k->activated_courses_id==$id->id) {
                                                $total_new =$k;
                                            }
                                        }
                                
                                        if ($total_new!="") {
                                            $upte_total = TotalAssistants::find($total_new->id);
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
                                        }
                                    } else {
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
                                        'assistant_id' =>$assistantId,
                                ]);
                                    }
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
                                    'assistant_id' =>$assistantId,
                                    ]);
                              }
                            } 
                            elseif(($s->pivot->done_practical_2 == 0 &&$s->pivot->done_practical != $assistant->id&&$s->pivot->done_practical_3 != $assistant->id&& $s->pivot->attendence >= 60 )){ //to check the student not evluate this course to void evluate agin

                                $questionareNew = Questionnaire::create([
                                'activated_courses_id' =>  $id->id,
                                
                                ]);
                                if($request->comment){
                                    $qu = Questionnaire::find($questionareNew->id);
                                    $qu->comment = $request->comment;
                                    $qu->save();
                                    
                                }
                                $questionareNew->Question()->attach(15, ['answer' => $request->Answer_15]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(21, ['answer' => $request->Answer_21]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(22, ['answer' => $request->Answer_22]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(23, ['answer' => $request->Answer_23]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(24, ['answer' => $request->Answer_24]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(25, ['answer' => $request->Answer_25]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(26, ['answer' => $request->Answer_26]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(27, ['answer' => $request->Answer_27]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(28, ['answer' => $request->Answer_28]); // this function is used to insert new answer
                                $s->pivot->done_practical_2 =  $assistant->id;
                                $s->pivot->save();
                                $total = ActivatedCourses::find($id->id)->TotalAssistants;
                                if ($total) { //if total already exist in database make update only
                                    $assistant_total_id =[];
                                    foreach ($total  as $k) {
                                        $assistant_total_id []= $k->assistant_id;
                                    }
                                    if (in_array($assistantId, $assistant_total_id)) {
                                        $total_new="";
                                        $All_tototal =  TotalAssistants::Where('assistant_id', '=', $assistantId)->get();
                                        foreach ($All_tototal as $k) {
                                            if ($k->activated_courses_id==$id->id) {
                                                $total_new =$k;
                                            }
                                        }
                                
                                        if ($total_new!="") {
                                            $upte_total = TotalAssistants::find($total_new->id);
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
                                        }
                                    } else {
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
                                        'assistant_id' =>$assistantId,
                                ]);
                                    }
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
                                    'assistant_id' =>$assistantId,
                                    ]);
                              }
                            }
                            elseif(($s->pivot->done_practical_3 == 0 &&$s->pivot->done_practical != $assistant->id&&$s->pivot->done_practical_2 != $assistant->id&& $s->pivot->attendence >= 60 )){ //to check the student not evluate this course to void evluate agin

                                $questionareNew = Questionnaire::create([
                                'activated_courses_id' =>  $id->id,
                                
                                ]);
                                if($request->comment){
                                    $qu = Questionnaire::find($questionareNew->id);
                                    $qu->comment = $request->comment;
                                    $qu->save();
                                    
                                }
                                $questionareNew->Question()->attach(15, ['answer' => $request->Answer_15]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(21, ['answer' => $request->Answer_21]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(22, ['answer' => $request->Answer_22]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(23, ['answer' => $request->Answer_23]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(24, ['answer' => $request->Answer_24]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(25, ['answer' => $request->Answer_25]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(26, ['answer' => $request->Answer_26]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(27, ['answer' => $request->Answer_27]); // this function is used to insert new answer
                                $questionareNew->Question()->attach(28, ['answer' => $request->Answer_28]); // this function is used to insert new answer
                                $s->pivot->done_practical_3 =  $assistant->id;
                                $s->pivot->save();
                                $total = ActivatedCourses::find($id->id)->TotalAssistants;
                                if ($total) { //if total already exist in database make update only
                                    $assistant_total_id =[];
                                    foreach ($total  as $k) {
                                        $assistant_total_id []= $k->assistant_id;
                                    }
                                    if (in_array($assistantId, $assistant_total_id)) {
                                        $total_new="";
                                        $All_tototal =  TotalAssistants::Where('assistant_id', '=', $assistantId)->get();
                                        foreach ($All_tototal as $k) {
                                            if ($k->activated_courses_id==$id->id) {
                                                $total_new =$k;
                                            }
                                        }
                                
                                        if ($total_new!="") {
                                            $upte_total = TotalAssistants::find($total_new->id);
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
                                        }
                                    } else {
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
                                        'assistant_id' =>$assistantId,
                                ]);
                                    }
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
                                    'assistant_id' =>$assistantId,
                                    ]);
                              }
                            }
                            else {
                                return response()->json(['message'=>'this course alredy done questionnire '], 200);
                            }
                        }
                    }
                }else {
                    return response()->json(['message'=>'this is course not activated for you'], 200);
                }
        
            } else {
                return response()->json(['message'=>'this course not have practical'], 200);
            }
        
            return response()->json(['message'=>'success'], 201);
        }


}


