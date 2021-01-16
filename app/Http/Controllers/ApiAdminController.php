<?php

namespace App\Http\Controllers;
use App\ActivatedCourses;
use App\Assistant;
use App\Course;
use App\Doctor;
use App\DoctorDuties;
use App\Http\Middleware\isAdmin;
use App\Http\Resources\AdminResource;
use App\SkillsAssistant;
use App\Total;
use App\TotalAssistants;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Questionnaire;
use App\EmptyFiles;
use App\Program;
use App\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use App\form12;
use App\form16;
use App\form_11a;
use App\ILOs;
use Response;
class ApiAdminController extends Controller
{  
    
    public function __construct()
    {
        $this->middleware('auth:doctor-api');
    }
     
   public function getMissingFileProgram($id){
        
        $program = Program::find($id);
        if( $program == ''){
            return response()->json(['error'=>'not valid program id   :('],200);
        }else{
             
            if($program->form15_file_path==NULL){
                $form15 = "true";
            }else{
                $form15 = "false";
            } 

            if($program->form11b_file_path==NULL){
                $form11b = "true";
            }else{
                $form11b = "false";
            } 
            if($program->form13_file_path==NULL){
                $form13 = "true";
            }else{
                $form13 = "false";
            } 
          $missing =[
            "Form15" => $form15,
            "Form11b"=> $form11b,
            "Form13"=>$form13,
          ];
           // return $missing ;
            return response()->json($missing ,200);
        }
   }




    public function getAssistantRate($id)
    {   
         $assistant = Assistant::find($id);
        if($assistant ==""){
            return response()->json(['error'=>'not found assistant id enter valid id  :('],200);
        }else{
              
             $total_each_course =0;
              $all_total =  TotalAssistants::Where('assistant_id','=',$id)->get();
           if($all_total =='[]'){
               return 0;
           }
            $count_all_courses = $all_total->count();
          //  $total_course
         // return $all_total->toArray();
            foreach( $all_total as $k){
                   $sum = 0;   
                   $count=0;
                   $average = 0;
                   $t_15 =  $k->t_15; 
                   $t_21 = $k->t_21;
                   $t_22=$k->t_22;
                   $t_23 = $k->t_23;
                   $t_24 = $k->t_24;
                   $t_25 = $k->t_25;
                   $t_26 = $k->t_26;
                   $t_27 =$k->t_27;
                   $t_28 = $k->t_28;
                   $count = $k->count;
                   $sum =   $t_15+$t_21+$t_22+$t_23+$t_24+$t_25+$t_26+$t_27+$t_28 ;
                   $average = ($sum)/(45*$count);
                   $total_each_course =$total_each_course+$average;
                  
            }
            return  $total_each_course*5/$count_all_courses;

        }





 
        
    } 



    public function downloadExamFile($activated_courses_id)
    {
        try{
            $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
        if($all_file =='[]'){
           return response()->json(['error'=>'not found file for this Activated course   :('],200);
        }else{
           foreach( $all_file as $k){
               $file_id = $k->id;
           }
           $file=File::find($file_id);
           $name= $file->exam_original_filename;
           //return Storage::download($file->exam_file_path, $name);
           ob_end_clean();
           return response()->download(storage_path('app/public/'.$file->exam_file_path),$name);
        }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '], 200);
         }
        
    } 

    public function downloadAnswerFile($activated_courses_id)
    {
       try{
        $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
        if($all_file =='[]'){
           return response()->json(['error'=>'not found file for this Activated course   :('],200);
        }else{
           foreach( $all_file as $k){
               $file_id = $k->id;
           }
           $file=File::find($file_id);
           $name= $file->answers_original_filename;
           ob_end_clean();
           return response()->download(storage_path('app/public/'.$file->answers_file_path),$name);
           
        }
       }catch (\Throwable $th) {
        return response()->json(['error'=>'not found form '], 200);
     }
        
    } 
    

    public function downloadForm12File($activated_courses_id)
    {
       try{
        $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
        if($all_file =='[]'){
           return response()->json(['error'=>'not found file for this Activated course   :('],200);
        }else{
           foreach( $all_file as $k){
               $file_id = $k->id;
           }
           $all_form12= form12::Where('file_id','=',$file_id)->get();
            if($all_form12 =='[]'){
                return response()->json(['error'=>'not found form 12 for this Activated course   :('],200);
            }
            foreach($all_form12 as $k){
                $form12_id = $k->id;
            }
            $exist_form12=form12::find($form12_id);
           $name= $exist_form12->original_filename;
           //return Storage::download($exist_form12->file_path, $name);
           ob_end_clean();
           return response()->download(storage_path('app/public/'.$exist_form12->file_path),$name);
        }
        
       }catch (\Throwable $th) {
        return response()->json(['error'=>'not found form '], 200);
     }
    }

    public function downloadForm16File($activated_courses_id)
    {
       try{
        $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
        if($all_file =='[]'){
           return response()->json(['error'=>'not found file for this Activated course   :('],200);
        }else{
           foreach( $all_file as $k){
               $file_id = $k->id;
           }
           $all_form12= form16::Where('file_id','=',$file_id)->get();
            if($all_form12 =='[]'){
                return response()->json(['error'=>'not found form 16 for this Activated course   :('],200);
            }
            foreach($all_form12 as $k){
                $form12_id = $k->id;
            }
            $exist_form12=form16::find($form12_id);
           $name= $exist_form12->original_filename;
           //return Storage::download($exist_form12->file_path, $name);
           ob_end_clean();
           return response()->download(storage_path('app/public/'.$exist_form12->file_path),$name);

        }
       }catch (\Throwable $th) {
        return response()->json(['error'=>'not found form '], 200);
     }
        
    } 
    public function downloadForm11aFile($activated_courses_id)
    {
       try{
        $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
        if($all_file =='[]'){
           return response()->json(['error'=>'not found file for this Activated course   :('],200);
        }else{
           foreach( $all_file as $k){
               $file_id = $k->id;
           }
           $all_form12= form_11a::Where('file_id','=',$file_id)->get();
            if($all_form12 =='[]'){
                return response()->json(['error'=>'not found form 11a for this Activated course   :('],200);
            }
            foreach($all_form12 as $k){
                $form12_id = $k->id;
            }
            $exist_form12=form_11a::find($form12_id);
           $name= $exist_form12->original_filename;
           //return Storage::download($exist_form12->file_path, $name);
           ob_end_clean();
           return response()->download(storage_path('app/public/'.$exist_form12->file_path),$name);

        }
        
       }catch (\Throwable $th) {
        return response()->json(['error'=>'not found form '], 200);
     }
    } 

    public function downloadILOsFile($activated_courses_id)
    {
      try{
            $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
            if($all_file =='[]'){
            return response()->json(['error'=>'not found file for this Activated course   :('],200);
            }else{
            foreach( $all_file as $k){
                $file_id = $k->id;
            }
            $all_form12= ILOs::Where('file_id','=',$file_id)->get();
                if($all_form12 =='[]'){
                    return response()->json(['error'=>'not found ILOs for this Activated course   :('],200);
                }
                foreach($all_form12 as $k){
                    $form12_id = $k->id;
                }
                $exist_form12=ILOs::find($form12_id);
            $name= $exist_form12->original_filename;
            //return Storage::download($exist_form12->file_path, $name);
             ob_end_clean();
               return response()->download(storage_path('app/public/'.$exist_form12->file_path),$name);
            }
       }catch (\Throwable $th) {
        return response()->json(['error'=>'not found form '], 200);
     }
        
    } 



    public function showAllProgram(){
        $Programs=[];
        $all_program = Program::all();
        if($all_program =='[]'){
            return response()->json(['massage' => 'not found any program'], 200);
        }else{
            
            foreach($all_program as $k){
                $Programs[]= [
                    'id' =>$k->id,
                    'name'=>$k->name,
                    'hours' =>$k->hours,
                    'category'=>$k->category,
                    'activated'=>$k->activated,
                ];
      
              }
              return response()->json($Programs, 200);
               
        }
        
    }
    public function displayExamfile($activated_courses_id)
    {    //this function applied only in file pdf

          
      try{
        $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
        if($all_file =='[]'){
           return response()->json(['error'=>'not found file for this Activated course   :('],200);
        }else{
           foreach( $all_file as $k){
               $file_id = $k->id;
           }
           $file=File::find( $file_id);
           $mimeType = Storage::mimeType($file->exam_file_path);
           $name= $file->exam_original_filename;
           if($mimeType!='application/pdf'){
                
               //return Storage::download($file->exam_file_path, $name);
               ob_end_clean();
               return response()->download(storage_path('app/public/'.$file->exam_file_path),$name);

           }
               $contents = Storage::get($file->exam_file_path);
               $response = Response::make($contents, 200); 
               $response->header('Content-Type', 'application/pdf',$name);
               return $response;
        }
      
      }catch (\Throwable $th) {
        return response()->json(['error'=>'not found form '], 200);
     }
           
    } 

    public function displayAnswerfile($activated_courses_id)
    {    //this function applied only in file pdf

        try{
            $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
            if($all_file =='[]'){
               return response()->json(['error'=>'not found file for this Activated course   :('],200);
            }else{
               foreach( $all_file as $k){
                   $file_id = $k->id;
               }
               $file=File::find( $file_id);
               $mimeType = Storage::mimeType($file->answers_file_path);
               $name= $file->answers_original_filename;
               if($mimeType!='application/pdf'){
                    
                   //return Storage::download($file->answers_file_path, $name);
                   ob_end_clean();
                   return response()->download(storage_path('app/public/'.$file->answers_file_path),$name);
   
               }
                   $contents = Storage::get($file->answers_file_path);
                   $response = Response::make($contents, 200); 
                   $response->header('Content-Type', 'application/pdf',$name);
                   return $response;
            }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '], 200);
         }
                       
    }

    public function displayForm12file($activated_courses_id)
    {    //this function applied only in file pdf

          
         try{
            $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
            if($all_file =='[]'){
               return response()->json(['error'=>'not found file for this Activated course   :('],200);
            }else{
               foreach( $all_file as $k){
                   $file_id = $k->id;
               }
               $all_form12= form12::Where('file_id','=',$file_id)->get();
               if($all_form12 =='[]'){
                   return response()->json(['error'=>'not found form 12 for this Activated course   :('],200);
               }
               foreach($all_form12 as $k){
                   $form12_id = $k->id;
               }
               $exist_form12=form12::find($form12_id);
               $mimeType = Storage::mimeType($exist_form12->file_path);
               $name= $exist_form12->original_filename;
               if($mimeType!='application/pdf'){
                    
                  // return Storage::download($exist_form12->file_path, $name);
                  ob_end_clean();
                  return response()->download(storage_path('app/public/'.$exist_form12->file_path),$name);
   
               }
                   $contents = Storage::get($exist_form12->file_path);
                   $response = Response::make($contents, 200); 
                   $response->header('Content-Type', 'application/pdf',$name);
                   return $response;
            }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '], 200);
         }
       
           
    }


    public function displayForm16file($activated_courses_id)
    {    //this function applied only in file pdf

          try{
            $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
            if($all_file =='[]'){
               return response()->json(['error'=>'not found file for this Activated course   :('],200);
            }else{
               foreach( $all_file as $k){
                   $file_id = $k->id;
               }
               $all_form12= form16::Where('file_id','=',$file_id)->get();
               if($all_form12 =='[]'){
                   return response()->json(['error'=>'not found form 16 for this Activated course   :('],200);
               }
               foreach($all_form12 as $k){
                   $form12_id = $k->id;
               }
               $exist_form12=form16::find($form12_id);
               $mimeType = Storage::mimeType($exist_form12->file_path);
               $name= $exist_form12->original_filename;
               if($mimeType!='application/pdf'){
                    
                  // return Storage::download($exist_form12->file_path, $name);
                  ob_end_clean();
                  return response()->download(storage_path('app/public/'.$exist_form12->file_path),$name);

   
               }
                   $contents = Storage::get($exist_form12->file_path);
                   $response = Response::make($contents, 200); 
                   $response->header('Content-Type', 'application/pdf',$name);
                   return $response;
            }
          }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '], 200);
         }
       
           
    } 


    public function displayForm11afile($activated_courses_id)
    {    //this function applied only in file pdf

        try{
            $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
            if($all_file =='[]'){
               return response()->json(['error'=>'not found file for this Activated course   :('],200);
            }else{
               foreach( $all_file as $k){
                   $file_id = $k->id;
               }
               $all_form12= form_11a::Where('file_id','=',$file_id)->get();
               if($all_form12 =='[]'){
                   return response()->json(['error'=>'not found form 11a for this Activated course   :('],200);
               }
               foreach($all_form12 as $k){
                   $form12_id = $k->id;
               }
               $exist_form12=form_11a::find($form12_id);
               $mimeType = Storage::mimeType($exist_form12->file_path);
               $name= $exist_form12->original_filename;
               if($mimeType!='application/pdf'){
                    
                   //return Storage::download($exist_form12->file_path, $name);
                   ob_end_clean();
                   return response()->download(storage_path('app/public/'.$exist_form12->file_path),$name);
   
               }
                   $contents = Storage::get($exist_form12->file_path);
                   $response = Response::make($contents, 200); 
                   $response->header('Content-Type', 'application/pdf',$name);
                   return $response;
            }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '], 200);
         }
       
           
    } 

    public function displayILOsfile($activated_courses_id)
    {    //this function applied only in file pdf

       try{
              
         $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
         if($all_file =='[]'){
            return response()->json(['error'=>'not found file for this Activated course   :('],200);
         }else{
            foreach( $all_file as $k){
                $file_id = $k->id;
            }
            $all_form12= ILOs::Where('file_id','=',$file_id)->get();
            if($all_form12 =='[]'){
                return response()->json(['error'=>'not found ILOs for this Activated course   :('],200);
            }
            foreach($all_form12 as $k){
                $form12_id = $k->id;
            }
            $exist_form12=ILOs::find($form12_id);
            $mimeType = Storage::mimeType($exist_form12->file_path);
            $name= $exist_form12->original_filename;
            if($mimeType!='application/pdf'){
                 
               // return Storage::download($exist_form12->file_path, $name);
               ob_end_clean();
               return response()->download(storage_path('app/public/'.$exist_form12->file_path),$name);

            }
                $contents = Storage::get($exist_form12->file_path);
                $response = Response::make($contents, 200); 
                $response->header('Content-Type', 'application/pdf',$name);
                return $response;
         }
       
       }catch (\Throwable $th) {
        return response()->json(['error'=>'not found form '], 200);
     }
           
    } 



    public function get_comment($id,$doctor_id)
    {      
        $activated_course =ActivatedCourses::find($id);
        if($activated_course!='[]'){
            $doctor=Doctor::find($doctor_id);
            if($doctor==''){
                return response()->json(['error'=>'invalid doctor id enter valid doctor id'],200);
            }
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
                    
                    return response()->json( $all_coumment,201);
                  }else{
                    return response()->json(['massage'=>'not found any comment for this course'],200);
                  }
                 
                }else{ return response()->json(['error'=>'doctor not have this is cours'],200);}

            } else{ return response()->json(['error'=>'not found any cours for current doctor'],200);}

        }else{
            return response()->json(['error'=>'this course not found enter valid id'],200);
        }
      
    }

    public function show_all_doctor()
    {
        $all_doctor = Doctor::all();
        $final_resulte = [];
        foreach ($all_doctor as $k) {
            $all_duties = [];
            $k->teaching_hours =$this->computeTeachingHour($k->id);
            $k->save();
           
            try {
               
                $docor_duties = Doctor::find($k->id)->DoctorDuties()->get();
                if ($docor_duties =='[]'){
                    $all_duties  = "not found duties";
                }else{
                    foreach ($docor_duties as $duties) {
                    
                        $all_duties  []=$duties;
                    
                    }
                }
                
                
            } catch (\Throwable $th) {
                $all_duties  = "not found duties";
            }
          $doctor=[
                 'id' =>$k->id,
                 'name' =>$k->name,
                 'email' =>$k->email,
                 'teaching_hours' =>$k->teaching_hours,
                 'is_working' =>$k->is_working,
                 'speciality'=>$k->speciality,
                 'duties' => $all_duties,
                ] ;

                $final_resulte [] = $doctor;
        }
      
        
         
        return AdminResource::collection($final_resulte);
    }
    public function computeTeachingHour($id)
    {
        
        $doctor = Doctor::find($id);
        $activatecourse_all = [];
        foreach ($doctor->ActivatedCourses as $k) {
            $activatecourse =  ActivatedCourses::find($k->id);
            $activatecourse_all[]= $activatecourse->Course->theoritical_hours;
        }
        return  array_sum($activatecourse_all);
    }
    public function computeTeachingHour_assistant($id)
    {
        
        $assistant = Assistant::find($id);
        $activatecourse_all = [];
        foreach ($assistant->ActivatedCourses as $k) {
            $activatecourse =  ActivatedCourses::find($k->id);
            $activatecourse_all[]= $activatecourse->Course->practical_hours;
        }
        return  array_sum($activatecourse_all);
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'duties' => ['required', 'min:5', 'max:100'],
        ]);
    }
    public function update_duties(DoctorDuties $id,Request $request)
    {   
       
        try {
            $this->validator($request->all())->validate();
        } catch (\Throwable $th) {
            return response()->json($this->validator($request->all())->errors(),200);
        }
      
      
        try {
        
        $duties = DoctorDuties::find($id->id);
        $duties->duties = $request->duties;
        $duties->save();
        return response()->json(['message' =>'success update duties'], 201);
       } catch (\Throwable $th) {
        return response()->json(['message' =>'can not update duties'],200);
       }
         
    }

    public function showAssistant()
    {          
        $all_assistant = Assistant::all();
        $final_resulte = [];
        foreach ($all_assistant as $k) {
            $all_skillls = [];
            $k->teaching_hours =$this->computeTeachingHour_assistant($k->id);
            $k->save();
           
            try {
               
                $assistant_skills = Assistant::find($k->id)->SkillsAssistant()->get();
                if ($assistant_skills =='[]'){
                    $all_skillls  = "not found skills";
                }else{
                    foreach ($assistant_skills as $skills) {
                    
                        $all_skillls  []=$skills;
                    
                    }
                }
                
                
            } catch (\Throwable $th) {
                $all_skillls  = "not found skills";
            }
          $assistant=[
                 'id' =>$k->id,
                 'name' =>$k->name,
                 'email' =>$k->email,
                 'rate' => $this->getAssistantRate($k->id),
                 'teaching_hours' =>$k->teaching_hours,
                 'is_working' =>$k->is_working,
                 'speciality'=>$k->speciality,
                 'skills' => $all_skillls,
                ] ;

                $final_resulte [] = $assistant;
        }
      
        
         
        return AdminResource::collection($final_resulte);
    }
    protected function validatorskills(array $data)
    {
        return Validator::make($data, [
            'skills' => ['required', 'min:2', 'max:100'],
        ]);
    }
    public function update_skills(SkillsAssistant $id,Request $request)
    {   
       
        try {
            $this->validatorskills($request->all())->validate();
        } catch (\Throwable $th) {
            return response()->json($this->validatorskills($request->all())->errors(),200);
        }
      
        try {
        
        $skills = SkillsAssistant::find($id->id);
        $skills->skills = $request->skills;
         $skills->save();
        return response()->json(['message' =>'success update skills'], 201);
       } catch (\Throwable $th) {
        return response()->json(['message' =>'can not update skills'],200);
       }
         
    }

    public function  courseinfo()
    { 
       $all_activated = ActivatedCourses::all();
       $final_reslute= [];
       if( !$all_activated->isEmpty()){
        foreach ($all_activated as $k) {
            $course =ActivatedCourses::find($k->id)->Course;
            $activatecourse =ActivatedCourses::find($k->id);
             
             
            $missing_file =ActivatedCourses::find($k->id)->File;

             $coordinatorid =  $activatecourse->coordinator_id;
             $coordinatordoctor =  Doctor::find($coordinatorid);
             if(  ($coordinatorid!=0)&&($coordinatordoctor!="") ){
           
           
            $Doctor_id = $coordinatordoctor->id;
            $Doctor_name = $coordinatordoctor->name;
           }
           else{ $doctor =ActivatedCourses::find($k->id)->Doctor;
            foreach ($doctor  as $kdoctor) {
                $Doctor_id =$kdoctor->id;
                $Doctor_name =$kdoctor->name;
 
            }}
             
           if ($missing_file =='') {
            $missing ='yes';
            $file_id  =NULL;
           } else {
             $missingFlag =false;
               $AllMissingFile =$this->getMissingFileCourseInfo($activatecourse->id);
             foreach($AllMissingFile as $k){
                  //return  $k;
                    if($k =='true'){
                      $missingFlag =true; 
                    }
                
                  
             }
             
          
                if($missingFlag ==true){
                    $missing ='yes';
                }else{
                    $missing ='No';
                }
                
                $file_id  =$missing_file->id;
           
           }
           
            
            $tt = [
                'Doctor_name' =>$Doctor_name,
                'Doctor_id'=> $Doctor_id,
                'Activated_course_id' => $activatecourse->id,
                'course_code' =>$course->code,
                'course_name' => $course->name,
                'semester' => $activatecourse->semester,
                'year' => $activatecourse->year,
                'description' => $course->description,
                'missing_file' => $missing,
                'file_id ' => $file_id ,
                
                ];
            $final_reslute[]= $tt;
        }
        return  $final_reslute;
       }else{
        return response()->json(['error' =>'not found activated course'],200);
       }
    }

    public function show_total_doctor( $doctor_id, $activatecourse_id)
    {   
        
        $doctor=Doctor::find($doctor_id);
        $activatecourse=ActivatedCourses::find($activatecourse_id);
        if ($doctor !='') {
           if ($activatecourse!='') {
            $total_all = [];
            $All_course_id = [];
            foreach ($doctor->ActivatedCourses as $k) {
                $All_course_id[]=$k->id;
    
            }
           
            if(in_array($activatecourse->id, $All_course_id)){
                
                $total_get =  Total::where('activated_courses_id', '=',$activatecourse->id)->get();
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
                        $total_practical= $this->get_all_total_practical($activatecourse->id);
                        if($total_practical!=""){
                            $total []=$total_practical->original;
                        }
                           return   response()->json($total,201);
                           
                    }else{return response()->json(['message'=>'not found total yet'],200);}
                        
                }else{return response()->json(['message'=>'not found total yet'],200);}
                    
                 
                
    
            }else{ return response()->json(['message'=>'you not allow to access this course :('],200);}





           } else {
               return response()->json(['error'=>'this id for activatecourse not found enter valid id'],401);
           }
           
        } else {
            return response()->json(['error'=>'this id for doctor not found enter valid id'],401);
        }
       
       // return $doctor;
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
                
               try {
                   $assistant = Assistant::find($ke);
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
                           $all_totai_assistant []= $total;
                           }
   
       
                              
                       }else{return response()->json(['message'=>'not found total yet'],200);}
                           
                   }else{return response()->json(['message'=>'not found total yet'],200);}
                       
                    
                   
       
               }else{ return response()->json(['message'=>'you not allow to access this course :('],200);}
   
            }
   
            return   response()->json($all_totai_assistant,201);
          
        }
      
       

       
    }


    public function get_all_total_assistant($assistant_id, $activatecourse_id)
    {
        $assistant = Assistant::find($assistant_id);
        $activatecourse=ActivatedCourses::find($activatecourse_id);
       if ($assistant!='') {
          if ($activatecourse!='') {
            $total_all = [];
            $All_course_id = [];
            foreach ($assistant->ActivatedCourses as $k) {
                $All_course_id[]=$k->id;
    
            }
           
            if(in_array($activatecourse->id, $All_course_id)){
                
                $total_get = TotalAssistants::where('activated_courses_id', '=',$activatecourse->id)->get();
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
    
         
            } else {
                return response()->json(['error'=>'this id for activated course not found enter valid id'],200);
          }
          
       } else {
            return response()->json(['error'=>'this id for assistant not found enter valid id'],200);
       }
       
      
             
    }
    protected function validatorFile(array $data)
    {
        return Validator::make($data, [
            'file' => 'required|mimes:doc,pdf,docx' ,
            
        ]);
    }
    public function storeEmptyFile(Request $request)
    {    
        
       try{
        try {
            $this->validatorFile($request->all())->validate();
         } catch (\Throwable $th) {
            return response()->json($this->validatorFile($request->all())->errors(),200);
         }
        $path = Storage::putFile('EmptyFile', $request->file('file'));//$request->file('file')->store('images');
        $file = $request->file('file');    
        $originalName = $file->getClientOriginalName();
          
        $newfile= EmptyFiles::create([
            "original_filename"=>$originalName,
            "file_path"=> $path,
        ]);
        return  response()->json(['message'=>'success upload file'],201);;
       }catch (\Throwable $th) {
        return response()->json(['error'=>'not found form '],200);
     }
    } 

    public function displayfile($file_id)
    {    //this function applied only in file pdf
         try{
            $file =EmptyFiles::find($file_id);
            if($file==''){
             return response()->json(['error'=>'not found file enter valid id :('],200);
            }else{
                
                   $mimeType = Storage::mimeType($file->file_path);
                 if($mimeType!='application/pdf'){
                       $name= $file->original_filename;
                     //return Storage::download($file->file_path, $name);
                     ob_end_clean();
                     return response()->download(storage_path('app/public/'.$file->file_path),$name);
     
                 }
                     $contents = Storage::get($file->file_path);
                     $response = Response::make($contents, 200); 
                     $response->header('Content-Type', 'application/pdf');
                     return $response;
            }
         }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '],200);
         }
           
    } 


    public function downloadEmptyFile($file_id)
    {
      try{
        $file =EmptyFiles::find($file_id);
       
        if($file==''){
            
            return response()->json(['error'=>'not found file enter valid id :('],200);
        
        }else{

            $name= $file->original_filename;
            //return Storage::download($file->file_path, $name);
            ob_end_clean();
            return response()->download(storage_path('app/public/'.$file->file_path),$name);
        }
      }catch (\Throwable $th) {
         return response()->json(['error'=>'not found form '],200);
      }
    }  

    public function deleteEmptyFile($file_id)
    {
        try{
            $file =EmptyFiles::find($file_id);
       
            if($file==''){
                
                return response()->json(['error'=>'not found file enter valid id :('],200);
            
            }else{

                Storage::delete($file->file_path);
                $file->delete();
            
                return  response()->json(['message'=>'success delete file'],201);
            }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '],200);
        }
        
    }
    public function getAllEmptyFile()
    {
        $file =EmptyFiles::all();  
        if($file=='[]'){
            
            return response()->json(['error'=>'not found any file yet:('],200);
        
        }else{

             
            return  response()->json($file,201);
        }
        
    }



    public function storeForm15File(Request $request,$program_id)
    {    
           
        try {
              $this->validatorFile($request->all())->validate();
        } catch (\Throwable $th) {
            return response()->json($this->validatorFile($request->all())->errors(),200);
        }
        $exist_file =Program::find($program_id);
        if($exist_file==""){
            return response()->json(['error'=>'not found this program in valid program'],200);
        }else{

            $path = Storage::putFile('Form15', $request->file('file'));//$request->file('file')->store('images');
            $file = $request->file('file');    
            $originalName = $file->getClientOriginalName();
            
            if($exist_file->form15_file_path==NULL){
                $exist_file->form15_original_filename=$originalName;
                $exist_file->form15_file_path=$path;
                $exist_file->save();
            }else{
                Storage::delete($exist_file->form15_file_path);
                $exist_file->form15_original_filename=$originalName;
                $exist_file->form15_file_path=$path;
                $exist_file->save();
            }
          
        }
      
        return  response()->json(['message'=>'success upload form 15'],201);
    } 

    public function displayForm15file($program_id)
    {    //this function applied only in file pdf

        try{
            $file =Program::find($program_id);
            if($file==""){
                return response()->json(['error'=>'not found this program in valid program'],200);
            }else{
                $mimeType = Storage::mimeType($file->form15_file_path);
                $name= $file->form15_original_filename;
                if($mimeType!='application/pdf'){
                                
                    //return Storage::download($file->form15_file_path, $name);
                    ob_end_clean();
                    return response()->download(storage_path('app/public/'.$file->form15_file_path),$name);
                }
                $contents = Storage::get($file->form15_file_path);
                $response = Response::make($contents, 200); 
                $response->header('Content-Type', 'application/pdf',$name);
                return $response;                    
        }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '],200);
        }
           
    }
       
           
    public function downloadForm15File($program_id)
    {
        try{
            $file =Program::find($program_id);
            if($file==""){
                return response()->json(['error'=>'not found this program in valid program'],200);
            }else{
            
            $name= $file->form15_original_filename;
            //return Storage::download($file->form15_file_path, $name);
            ob_end_clean();
            return response()->download(storage_path('app/public/'.$file->form15_file_path),$name);
            }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '],200);
        }
        
    } 
    
    public function deleteForm15File($program_id)
    {
        try{
            $file =Program::find($program_id);
            if($file==""){
                return response()->json(['error'=>'not found this program in valid program'],200);
            }else{
                
            Storage::delete($file->form15_file_path);
            $file->form15_original_filename =NULL;
            $file->form15_file_path =NULL;
            $file->save();
            
            return  response()->json(['message'=>'success delete form 15'],201);
            }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '],200);
        }
        
    } 
    
    public function storeForm11bFile(Request $request,$program_id)
    {    
           
        try {
              $this->validatorFile($request->all())->validate();
        } catch (\Throwable $th) {
            return response()->json($this->validatorFile($request->all())->errors(),200);
        }
        $exist_file =Program::find($program_id);
        if($exist_file==""){
            return response()->json(['error'=>'not found this program in valid program'],200);
        }else{

            $path = Storage::putFile('Form11b', $request->file('file'));//$request->file('file')->store('images');
            $file = $request->file('file');    
            $originalName = $file->getClientOriginalName();
            
            if($exist_file->form11b_file_path==NULL){
                $exist_file->form11b_original_filename=$originalName;
                $exist_file->form11b_file_path=$path;
                $exist_file->save();
            }else{
                Storage::delete($exist_file->form11b_file_path);
                $exist_file->form11b_original_filename=$originalName;
                $exist_file->form11b_file_path=$path;
                $exist_file->save();
            }
          
        }
      
        return  response()->json(['message'=>'success upload form 11b'],201);
    } 

    public function displayForm11bfile($program_id)
    {    //this function applied only in file pdf

        try{
            $file =Program::find($program_id);
            if($file==""){
                return response()->json(['error'=>'not found this program in valid program'],200);
            }else{
                $mimeType = Storage::mimeType($file->form11b_file_path);
                $name= $file->form11b_original_filename;
                if($mimeType!='application/pdf'){
                                
                   // return Storage::download($file->form11b_file_path, $name);
                   ob_end_clean();
                   return response()->download(storage_path('app/public/'.$file->form11b_file_path),$name);
                }
                $contents = Storage::get($file->form11b_file_path);
                $response = Response::make($contents, 200); 
                $response->header('Content-Type', 'application/pdf',$name);
                return $response;                    
        }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '],200);
        }
           
    }
       
           
    public function downloadForm11bFile($program_id)
    {
        try{
            $file =Program::find($program_id);
            if($file==""){
                return response()->json(['error'=>'not found this program in valid program'],200);
            }else{
            
            $name= $file->form11b_original_filename;
            //return Storage::download($file->form11b_file_path, $name);
            ob_end_clean();
            return response()->download(storage_path('app/public/'.$file->form11b_file_path),$name);
            }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '],200);
        }
        
    } 
    
    public function deleteForm11bFile($program_id)
    {
        try{
            $file =Program::find($program_id);
            if($file==""){
                return response()->json(['error'=>'not found this program in valid program'],200);
            }else{
                
            Storage::delete($file->form15_file_path);
            $file->form11b_original_filename =NULL;
            $file->form11b_file_path =NULL;
            $file->save();
            
            return  response()->json(['message'=>'success delete form 11b'],201);
            }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '],200);
        }
        
    } 



    public function storeForm13File(Request $request,$program_id)
    {    
           
        try {
              $this->validatorFile($request->all())->validate();
        } catch (\Throwable $th) {
            return response()->json($this->validatorFile($request->all())->errors(),200);
        }
        $exist_file =Program::find($program_id);
        if($exist_file==""){
            return response()->json(['error'=>'not found this program in valid program'],200);
        }else{

            $path = Storage::putFile('Form13', $request->file('file'));//$request->file('file')->store('images');
            $file = $request->file('file');    
            $originalName = $file->getClientOriginalName();
            
            if($exist_file->form13_file_path==NULL){
                $exist_file->form13_original_filename=$originalName;
                $exist_file->form13_file_path=$path;
                $exist_file->save();
            }else{
                Storage::delete($exist_file->form13_file_path);
                $exist_file->form13_original_filename=$originalName;
                $exist_file->form13_file_path=$path;
                $exist_file->save();
            }
          
        }
      
        return  response()->json(['message'=>'success upload form 13'],201);
    } 

    public function displayForm13file($program_id)
    {    //this function applied only in file pdf

        try{
            $file =Program::find($program_id);
            if($file==""){
                return response()->json(['error'=>'not found this program in valid program'],200);
            }else{
                $mimeType = Storage::mimeType($file->form13_file_path);
                $name= $file->form13_original_filename;
                if($mimeType!='application/pdf'){
                                
                    //return Storage::download($file->form13_file_path, $name);
                    ob_end_clean();
                    return response()->download(storage_path('app/public/'.$file->form13_file_path),$name);
                }
                $contents = Storage::get($file->form13_file_path);
                $response = Response::make($contents, 200); 
                $response->header('Content-Type', 'application/pdf',$name);
                return $response;                    
        }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '],200);
        }
           
    }
        
    public function downloadForm13File($program_id)
    {
        try{
            $file =Program::find($program_id);
            if($file==""){
                return response()->json(['error'=>'not found this program in valid program'],200);
            }else{
            
            $name= $file->form13_original_filename;
              //return Storage::download($file->form13_file_path, $name);
              ob_end_clean();
              return response()->download(storage_path('app/public/'.$file->form13_file_path),$name);
            }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '],200);
        }
        
    } 
    
    public function deleteForm13File($program_id)
    {
        try{
            $file =Program::find($program_id);
            if($file==""){
                return response()->json(['error'=>'not found this program in valid program'],200);
            }else{
                
            Storage::delete($file->form13_file_path);
            $file->form13_original_filename =NULL;
            $file->form13_file_path =NULL;
            $file->save();
            
            return  response()->json(['message'=>'success delete form 13'],201);
            }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '],200);
        }
        
    } 

    public function getMissingFile($activated_courses_id)
    {   
        $activated_course = ActivatedCourses::find($activated_courses_id);
        if( $activated_course==""){
            return response()->json(['error'=>'not found Activated Courses enter valid id'], 200);
        }else{
            $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
            if($all_file =='[]'){
                $form12 ='true';
                $form11a ='true';
                $ILOs ='true';
                $form16 ='true';
                $exam ='true';
                $answer='true';

            }else{
                foreach( $all_file as $k){
                    $file_id = $k->id;
                }
                  $exist_file=File::find( $file_id);
                
                //return form_11a::Where('file_id','=',$file_id)->get();
                if(ILOs::Where('file_id','=',$file_id)->get()=='[]'){
                    $ILOs ='true';
                }else{
                    $ILOs ='false';
                }
                if(form16::Where('file_id','=',$file_id)->get()=='[]'){
                    $form16 ='true';
                }else{
                    $form16 ='false';
                }
                if(form12::Where('file_id','=',$file_id)->get()=='[]'){
                    $form12 ='true';
                }else{
                    $form12 ='false';
                }
                if(form_11a::Where('file_id','=',$file_id)->get()=='[]'){
                    $form11a ='true';
                }else{
                    $form11a ='false';
                }
                if($exist_file->exam_file_path==NULL){
                    $exam ='true';
                }else{
                    $exam ='false';
                }
                if($exist_file->answers_file_path== NULL){
                    $answer ='true';
                }else{
                    $answer ='false';
                }
            }
        }
       $missFile =[
         'Form12' =>$form12,
         'Form11a' =>$form11a,
         'ILOs'=>$ILOs,
         'Form16' =>$form16,
         'Exam' =>$exam,
         'Answer'=>$answer
       ];
       /**if(in_array(false,$missFile)){
        return "false";
        } */
       return  response()->json($missFile,201);
    } 


    public function getMissingFileCourseInfo($activated_courses_id)
    {   
        $activated_course = ActivatedCourses::find($activated_courses_id);
        if( $activated_course==""){
            return response()->json(['error'=>'not found Activated Courses enter valid id'], 200);
        }else{
            $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
            if($all_file =='[]'){
                $form12 ='true';
                $form11a ='true';
                $ILOs ='true';
                $form16 ='true';
                $exam ='true';
                $answer='true';

            }else{
                foreach( $all_file as $k){
                    $file_id = $k->id;
                }
                  $exist_file=File::find( $file_id);
                
                //return form_11a::Where('file_id','=',$file_id)->get();
                if(ILOs::Where('file_id','=',$file_id)->get()=='[]'){
                    $ILOs ='true';
                }else{
                    $ILOs ='false';
                }
                if(form16::Where('file_id','=',$file_id)->get()=='[]'){
                    $form16 ='true';
                }else{
                    $form16 ='false';
                }
                if(form12::Where('file_id','=',$file_id)->get()=='[]'){
                    $form12 ='true';
                }else{
                    $form12 ='false';
                }
                if(form_11a::Where('file_id','=',$file_id)->get()=='[]'){
                    $form11a ='true';
                }else{
                    $form11a ='false';
                }
                if($exist_file->exam_file_path==NULL){
                    $exam ='true';
                }else{
                    $exam ='false';
                }
                if($exist_file->answers_file_path== NULL){
                    $answer ='true';
                }else{
                    $answer ='false';
                }
            }
        }
       $missFile =[
          $form12,
          $form11a,
          $ILOs,
          $form16,
          $exam,
          $answer
       ];
       /**if(in_array(false,$missFile)){
        return "false";
        } */
       return   $missFile ;
    } 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        # code...
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
        //
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
}
