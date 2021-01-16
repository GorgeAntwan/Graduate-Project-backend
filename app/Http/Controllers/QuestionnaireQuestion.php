<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Question;
use Illuminate\Http\Request;

class QuestionnaireQuestion extends Controller
{
    public function question_theoritical()
    {  
         try  {
              
             
             $all_guestion = Question::all();
             $all_theoritical =[];
             foreach ($all_guestion as $k) {
                if($k->type=='theoritical'){
                    $all_theoritical []= $k; 
                }

             }
             return response()->json($all_theoritical, 201);
            } catch (\Throwable $th) {
             return response()->json(['message'=>'record not found'], 200);
             
            }
    }
    public function question_pratical()
    {    
        
      try  {
              
             
        $all_guestion = Question::all();
        $all_pratical =[];
        foreach ($all_guestion as $k) {
           if($k->type=='pratical'){
               $all_pratical []= $k;
           }

        }
        return response()->json($all_pratical, 201);
       } catch (\Throwable $th) {
        return response()->json(['message'=>'record not found'], 200);
        
       }
    }
    public function question_comment()
    {    
        
      try  {
              
             
        $all_guestion = Question::all();
        $comment =[];
        foreach ($all_guestion as $k) {
           if($k->type=='comment'){
               $comment []= $k;
           }

        }
        return response()->json($comment, 201);
       } catch (\Throwable $th) {
        return response()->json(['message'=>'record not found'], 200);
        
       }
    }
    

}
