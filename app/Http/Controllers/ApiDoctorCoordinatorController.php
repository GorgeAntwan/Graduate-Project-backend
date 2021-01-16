<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 
use Illuminate\Support\Facades\Storage;
use App\File;
use App\form12;
use App\form16;
use App\form_11a;
use App\ILOs;
use App\Program;
use App\Course;
use App\EmptyFiles;
use App\ActivatedCoursesStudents;
use App\ActivatedCourses;
use Response;
use Validator;
class ApiDoctorCoordinatorController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:doctor-api');
    }


  public function notfictionForMaassingFile(){
   
    $activatedCourses = ActivatedCourses::Where('coordinator_id','=',auth()->user()->id)->get();
    $activatedCorsesId= [];
    $notfication= [];
    foreach( $activatedCourses as $k){
        $activatedCorsesId [] =$k->id;
    }  
    foreach ($activatedCorsesId as $kk){
        foreach($this->getMissingFileForCoordinator($kk)as $miss){
            if($miss=='true'){
               $corseCode = ActivatedCourses::find($kk)->course_code ;
               $corseYear = ActivatedCourses::find($kk)->year ;
               $corseSemster = ActivatedCourses::find($kk)->semester ;
               $massage = 'you have massing Files in Course Code '.$corseCode.' in semester ' . $corseSemster . ' in year ' .$corseYear; 
               $notfication [] =$massage; break;
            }
        }
    } 
    return  $notfication;
  }

  public function getMissingFileForCoordinator($activated_courses_id)
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



    public function getMissingFileProgram($activate_course_id){
        
        try{
            $course_code = ActivatedCourses::find($activate_course_id)->course_code;
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found Activated Course Enter valid activated course id  '],200);
        }
        try{
            $program_id =Course::Find($course_code)->program_id;
           
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found Course'],200);
        } 

        $program = Program::find($program_id);
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



    public function downloadForm15File($activate_course_id)
    {   
        
        try{
          $course_code = ActivatedCourses::find($activate_course_id)->course_code;
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found Activated Course Enter valid activated course id  '],200);
        }
        try{
           $program_id =Course::Find($course_code)->program_id;
         
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found Course'],200);
        } 
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





    public function downloadForm11bFile($activate_course_id)
    {   

        try{
            $course_code = ActivatedCourses::find($activate_course_id)->course_code;
          }catch (\Throwable $th) {
              return response()->json(['error'=>'not found Activated Course Enter valid activated course id  '],200);
          }
          try{
             $program_id =Course::Find($course_code)->program_id;
           
          }catch (\Throwable $th) {
              return response()->json(['error'=>'not found Course'],200);
          } 
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




    public function downloadForm13File($activate_course_id)
    {

        try{
            $course_code = ActivatedCourses::find($activate_course_id)->course_code;
          }catch (\Throwable $th) {
              return response()->json(['error'=>'not found Activated Course Enter valid activated course id  '],200);
          }
          try{
             $program_id =Course::Find($course_code)->program_id;
           
          }catch (\Throwable $th) {
              return response()->json(['error'=>'not found Course'],200);
          } 
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

 
    public function getAllEmptyFile()
    {
        $file =EmptyFiles::all();  
        if($file=='[]'){
            
            return response()->json(['error'=>'not found any file yet:('],200);
        
        }else{

             
            return  response()->json($file,201);
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


    protected function validatorFile(array $data)
    {
        return Validator::make($data, [
            'file' => 'required|mimes:doc,pdf,docx' ,
            
        ]);
    } 
    public function storeExamFile(Request $request,$activated_courses_id)
    {    
        
       
        try {
              $this->validatorFile($request->all())->validate();
         } catch (\Throwable $th) {
            return response()->json($this->validatorFile($request->all())->errors(), 200);
         }
        $path = Storage::putFile('Exam', $request->file('file'));//$request->file('file')->store('images');
        $file = $request->file('file');    
        $originalName = $file->getClientOriginalName();
        $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
        if($all_file =='[]'){
            $course_code  =ActivatedCourses::find($activated_courses_id)->course_code;
            $program_id=  Course::find($course_code)->program_id;
            $newfile= File::create([
                "activated_courses_id" =>$activated_courses_id,
                "program_id" =>$program_id,
                "exam_original_filename"=>$originalName,
                "exam_file_path"=> $path, 
            ]);
            
        }else{
            //return $all_file;
            foreach( $all_file as $k){
                $file_id = $k->id;
            }
            $exist_file=File::find( $file_id);
            Storage::delete($exist_file->exam_file_path);
            $exist_file->exam_original_filename=$originalName;
            $exist_file->exam_file_path=$path;
            $exist_file->save();
        }
      
        return  response()->json(['message'=>'success upload file'],201);
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
                
               return Storage::download($file->exam_file_path, $name);

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




    public function storeAnswerFile(Request $request,$activated_courses_id)
    {      
        try{
            try {
                $this->validatorFile($request->all())->validate();
           } catch (\Throwable $th) {
              return response()->json($this->validatorFile($request->all())->errors(), 200);
           }
          $path = Storage::putFile('Answer', $request->file('file'));//$request->file('file')->store('images');
          $file = $request->file('file');    
          $originalName = $file->getClientOriginalName();
          $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
          if($all_file =='[]'){
            $course_code  =ActivatedCourses::find($activated_courses_id)->course_code;
            $program_id=  Course::find($course_code)->program_id;
            $newfile= File::create([
                "activated_courses_id" =>$activated_courses_id,
                "program_id" =>$program_id,
                "answers_original_filename"=>$originalName,
                "answers_file_path"=> $path, 
            ]);

               
          }else{
              //return $all_file;
              foreach( $all_file as $k){
                  $file_id = $k->id;
              }
              $exist_file=File::find( $file_id);
              Storage::delete($exist_file->answers_file_path);
              $exist_file->answers_original_filename=$originalName;
              $exist_file->answers_file_path=$path;
              $exist_file->save();
          }
        
          return  response()->json(['message'=>'success upload file'],201);
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
                  // ob_end_clean();
                  // return response()->file(storage_path('app/public/'.$file->answers_file_path),['Content-Type', 'application/pdf','Content-Disposition: attachment; filename="downloaded.pdf"']);
                   $contents = Storage::get($file->answers_file_path);
                   $response = Response::make($contents, 200); 
                   $response->header('Content-Type', 'application/pdf',$name);
                   return $response;


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
    
    public function deleteAnswerFile($activated_courses_id)
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
           Storage::delete($file->answers_file_path);
           $file->answers_original_filename =NULL;
           $file->answers_file_path =NULL;
           $file->save();
          
           return  response()->json(['message'=>'success delete file'],201);
        }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '], 200);
         }
        
    } 
    public function deleteExamFile($activated_courses_id)
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
           Storage::delete($file->exam_file_path);
           $file->exam_original_filename =NULL;
           $file->exam_file_path =NULL;
           $file->save();
          
           return  response()->json(['message'=>'success delete file'],201);
        }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '], 200);
         }
        
    } 




    public function storeForm12File(Request $request,$activated_courses_id)
    {  
        
        
       
        try {
              $this->validatorFile($request->all())->validate();
         } catch (\Throwable $th) {
            return response()->json($this->validatorFile($request->all())->errors(), 200);
         }
        $path = Storage::putFile('Form12', $request->file('file'));//$request->file('file')->store('images');
        $file = $request->file('file');    
        $originalName = $file->getClientOriginalName();
        $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
        if($all_file =='[]'){
            $course_code  =ActivatedCourses::find($activated_courses_id)->course_code;
            $program_id=  Course::find($course_code)->program_id;
            $newfile= File::create([
                "activated_courses_id" =>$activated_courses_id,
                "program_id" =>$program_id,
            ]);
             
            $file_id =$newfile->id;
        }else{
            //return $all_file;
            foreach( $all_file as $k){
                $file_id = $k->id;
            }
            
        }

          $all_form12= form12::Where('file_id','=',$file_id)->get();
        if($all_form12 =='[]'){
            $newform12= form12::create([
                "file_id" =>$file_id,
                "original_filename"=>$originalName,
                "file_path"=>$path,
            ]);
        }else{
            
            foreach( $all_form12 as $k){
                $form12_id = $k->id;
            }
            $exist_form12=form12::find($form12_id);
            Storage::delete($exist_form12->file_path);
            $exist_form12->original_filename=$originalName;
            $exist_form12->file_path=$path;
            $exist_form12->save();
      
        }



       
        return  response()->json(['message'=>'success upload form 12 '],201);
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
                    
                //   return Storage::download($exist_form12->file_path, $name);
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
    
    public function deleteForm12File($activated_courses_id)
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
            
           Storage::delete($exist_form12->file_path);
           $exist_form12->delete();
          
           return  response()->json(['message'=>'success delete form 12'],201);
        }
        
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '], 200);
         }
    } 
    

    public function storeForm16File(Request $request,$activated_courses_id)
    {    
        
       
        try {
              $this->validatorFile($request->all())->validate();
         } catch (\Throwable $th) {
            return response()->json($this->validatorFile($request->all())->errors(), 200);
         }
        $path = Storage::putFile('Form16', $request->file('file'));//$request->file('file')->store('images');
        $file = $request->file('file');    
        $originalName = $file->getClientOriginalName();
        $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
        if($all_file =='[]'){
            $course_code  =ActivatedCourses::find($activated_courses_id)->course_code;
            $program_id=  Course::find($course_code)->program_id;
            $newfile= File::create([
                "activated_courses_id" =>$activated_courses_id,
                "program_id" =>$program_id,
            ]);
            $file_id =$newfile->id;
        }else{
            //return $all_file;
            foreach( $all_file as $k){
                $file_id = $k->id;
            }
            
        }

          $all_form12= form16::Where('file_id','=',$file_id)->get();
        if($all_form12 =='[]'){
            $newform12= form16::create([
                "file_id" =>$file_id,
                "original_filename"=>$originalName,
                "file_path"=>$path,
            ]);
        }else{
            
            foreach( $all_form12 as $k){
                $form12_id = $k->id;
            }
            $exist_form12=form16::find($form12_id);
            Storage::delete($exist_form12->file_path);
            $exist_form12->original_filename=$originalName;
            $exist_form12->file_path=$path;
            $exist_form12->save();
      
        }



       
        return  response()->json(['message'=>'success upload form 16 '],201);
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
    
    public function deleteForm16File($activated_courses_id)
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
            
           Storage::delete($exist_form12->file_path);
           $exist_form12->delete();
          
           return  response()->json(['message'=>'success delete form 16'],201);
        }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '], 200);
         }
        
    } 

     


    public function storeForm11aFile(Request $request,$activated_courses_id)
    {    
        
       
        try {
              $this->validatorFile($request->all())->validate();
         } catch (\Throwable $th) {
            return response()->json($this->validatorFile($request->all())->errors(), 200);
         }
        $path = Storage::putFile('form_11a', $request->file('file'));//$request->file('file')->store('images');
        $file = $request->file('file');    
        $originalName = $file->getClientOriginalName();
        $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
        if($all_file =='[]'){
            $course_code  =ActivatedCourses::find($activated_courses_id)->course_code;
            $program_id=  Course::find($course_code)->program_id;
            $newfile= File::create([
                "activated_courses_id" =>$activated_courses_id,
                "program_id" =>$program_id,
            ]);
            $file_id =$newfile->id;
        }else{
            //return $all_file;
            foreach( $all_file as $k){
                $file_id = $k->id;
            }
            
        }

          $all_form12= form_11a::Where('file_id','=',$file_id)->get();
        if($all_form12 =='[]'){
            $newform12= form_11a::create([
                "file_id" =>$file_id,
                "original_filename"=>$originalName,
                "file_path"=>$path,
            ]);
        }else{
            
            foreach( $all_form12 as $k){
                $form12_id = $k->id;
            }
            $exist_form12=form_11a::find($form12_id);
            Storage::delete($exist_form12->file_path);
            $exist_form12->original_filename=$originalName;
            $exist_form12->file_path=$path;
            $exist_form12->save();
      
        }



       
        return  response()->json(['message'=>'success upload form 11a '],201);
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
    
    public function deleteForm11aFile($activated_courses_id)
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
            
           Storage::delete($exist_form12->file_path);
           $exist_form12->delete();
          
           return  response()->json(['message'=>'success delete form 11a'],201);
        }
        }catch (\Throwable $th) {
            return response()->json(['error'=>'not found form '], 200);
         }
        
    } 



    public function storeILOsFile(Request $request,$activated_courses_id)
    {    
        
       
        try {
              $this->validatorFile($request->all())->validate();
         } catch (\Throwable $th) {
            return response()->json($this->validatorFile($request->all())->errors(), 200);
         }
        $path = Storage::putFile('ILOs', $request->file('file'));//$request->file('file')->store('images');
        $file = $request->file('file');    
        $originalName = $file->getClientOriginalName();
        $all_file= File::Where('activated_courses_id','=',$activated_courses_id)->get();
        if($all_file =='[]'){
            $course_code  =ActivatedCourses::find($activated_courses_id)->course_code;
            $program_id=  Course::find($course_code)->program_id;
            $newfile= File::create([
                "activated_courses_id" =>$activated_courses_id,
                "program_id" =>$program_id,
            ]);
            $file_id =$newfile->id;
        }else{
            //return $all_file;
            foreach( $all_file as $k){
                $file_id = $k->id;
            }
            
        }

          $all_form12= ILOs::Where('file_id','=',$file_id)->get();
        if($all_form12 =='[]'){
            $newform12= ILOs::create([
                "file_id" =>$file_id,
                "original_filename"=>$originalName,
                "file_path"=>$path,
            ]);
        }else{
            
            foreach( $all_form12 as $k){
                $form12_id = $k->id;
            }
            $exist_form12=ILOs::find($form12_id);
            Storage::delete($exist_form12->file_path);
            $exist_form12->original_filename=$originalName;
            $exist_form12->file_path=$path;
            $exist_form12->save();
      
        }



       
        return  response()->json(['message'=>'success upload  ILOs'],201);
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
    
    public function deleteILOsFile($activated_courses_id)
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
            
           Storage::delete($exist_form12->file_path);
           $exist_form12->delete();
          
           return  response()->json(['message'=>'success delete form 11a'],201);
        }
       }catch (\Throwable $th) {
        return response()->json(['error'=>'not found form '], 200);
     }
        
    } 
    




}
