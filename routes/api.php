<?php

use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isCoordinator;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); 


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

    
    Route::post('doctor-register', 'AuthDoctorController@register');
    Route::post('doctor-login', 'AuthDoctorController@login');
    Route::get('doctor-logout', 'AuthDoctorController@logout');
    Route::post('doctor-refresh', 'AuthDoctorController@refresh');
    Route::post('doctor-me', 'AuthDoctorController@me');
    Route::post('doctor/rest-password', 'AuthDoctorController@restPassword');

    Route::post('assistant-register', 'AuthAssistantController@register');
    Route::post('assistant-login', 'AuthAssistantController@login');
    Route::get('assistant-logout', 'AuthAssistantController@logout');
    Route::post('assistant-refresh', 'AuthAssistantController@refresh');
    Route::post('assistant-me', 'AuthAssistantController@me');
    Route::post('assistant/rest-password', 'AuthAssistantController@restPassword');

    Route::post('student-register', 'AuthStudentController@register');
    Route::post('student-login', 'AuthStudentController@login');
    Route::get('student-logout', 'AuthStudentController@logout');
    Route::post('student-refresh', 'AuthStudentController@refresh');
    Route::post('student-me', 'AuthStudentController@me');
    Route::post('student/rest-password', 'AuthStudentController@restPassword');  
});

Route::group([

    'middleware' => 'isAdmin'
    

], function ($router) {

    Route::resource('ApiAdmin', 'ApiAdminController');
    Route::get('Admin/doctor', 'ApiAdminController@show_all_doctor');
    Route::post('ApiAdmin/updateduties/{id}', 'ApiAdminController@update_duties');
    Route::get('Admin/updateduties', 'ApiAdminController@update_duties');
    Route::get('Admin/get_comment/{id}/{doctor_id}', 'ApiAdminController@get_comment');
    Route::get('Admin/assistant', 'ApiAdminController@showAssistant');
    Route::get('Admin/courseinfo', 'ApiAdminController@courseinfo');
    Route::get('Admin/total_doctor/{doctor_id}/{activatecourse_id}', 'ApiAdminController@show_total_doctor');
    Route::get('Admin/total_assitant/{assistant_id}/{activatecourse_id}', 'ApiAdminController@get_all_total_assistant');
    Route::post('Admin/uploadEmptyFile', 'ApiAdminController@storeEmptyFile');
    Route::get('Admin/displayfile/{file_id}', 'ApiAdminController@displayfile'); 
    Route::get('Admin/downloadEmptyFile/{file_id}', 'ApiAdminController@downloadEmptyFile');
    Route::get('Admin/deleteEmptyFile/{file_id}', 'ApiAdminController@deleteEmptyFile');
    Route::get('Admin/getAllEmptyFile', 'ApiAdminController@getAllEmptyFile');

    //from15 in program
    Route::post('Admin/storeForm15File/{program_id}', 'ApiAdminController@storeForm15File');
    Route::get('Admin/displayForm15file/{program_id}', 'ApiAdminController@displayForm15file');
    Route::get('Admin/downloadForm15File/{program_id}', 'ApiAdminController@downloadForm15File');
    Route::get('Admin/deleteForm15File/{program_id}', 'ApiAdminController@deleteForm15File');

    //from11b in program
    Route::post('Admin/storeForm11bFile/{program_id}', 'ApiAdminController@storeForm11bFile');
    Route::get('Admin/displayForm11bfile/{program_id}', 'ApiAdminController@displayForm11bfile');
    Route::get('Admin/downloadForm11bFile/{program_id}', 'ApiAdminController@downloadForm11bFile');
    Route::get('Admin/deleteForm11bFile/{program_id}', 'ApiAdminController@deleteForm11bFile');

    //from13 in program
    Route::post('Admin/storeForm13File/{program_id}', 'ApiAdminController@storeForm13File');
    Route::get('Admin/displayForm13file/{program_id}', 'ApiAdminController@displayForm13file');
    Route::get('Admin/downloadForm13File/{program_id}', 'ApiAdminController@downloadForm13File');
    Route::get('Admin/deleteForm13File/{program_id}', 'ApiAdminController@deleteForm13File');

    //get missing file for doctor if value is true is file missing if value false the file is complete
    Route::get('Admin/getMissingFile/{activated_courses_id}', 'ApiAdminController@getMissingFile');
    //exam
    Route::get('Admin/displayExamfile/{activate_course_id}', 'ApiAdminController@displayExamfile');  
    Route::get('Admin/downloadExamFile/{activate_course_id}', 'ApiAdminController@downloadExamFile');  


    //answer
    Route::get('Admin/displayAnswerfile/{activate_course_id}', 'ApiAdminController@displayAnswerfile') ;  
    Route::get('Admin/downloadAnswerFile/{activate_course_id}', 'ApiAdminController@downloadAnswerFile');  

    //form 12
    Route::get('Admin/displayForm12file/{activate_course_id}', 'ApiAdminController@displayForm12file') ;  
    Route::get('Admin/downloadForm12File/{activate_course_id}', 'ApiAdminController@downloadForm12File');  


    //form 16
    Route::get('Admin/displayForm16file/{activate_course_id}', 'ApiAdminController@displayForm16file') ;  
    Route::get('Admin/downloadForm16File/{activate_course_id}', 'ApiAdminController@downloadForm16File');  


    //form 11a
    Route::get('Admin/displayForm11afile/{activate_course_id}', 'ApiAdminController@displayForm11afile') ; 
    Route::get('Admin/downloadForm11aFile/{activate_course_id}', 'ApiAdminController@downloadForm11aFile');  

    
    //ILOs
    Route::get('Admin/displayILOsfile/{activate_course_id}', 'ApiAdminController@displayILOsfile');  
    Route::get('Admin/downloadILOsFile/{activate_course_id}', 'ApiAdminController@downloadILOsFile') ;  


    // display all information about each program
    Route::get('Admin/showAllProgram', 'ApiAdminController@showAllProgram');

    Route::get('Admin/getAssistantRate/{assistant_id}', 'ApiAdminController@getAssistantRate');
    //get missing file for program 
    Route::get('Admin/getMissingFileProgram/{program_id}', 'ApiAdminController@getMissingFileProgram');
    
});


// doctor download form15 from  program table 
Route::get('Doctor/downloadForm15File/{activate_course_id}', 'ApiDoctorCoordinatorController@downloadForm15File');
// doctor download form11b from  program table 
Route::get('Doctor/downloadForm11bFile/{activate_course_id}', 'ApiDoctorCoordinatorController@downloadForm11bFile');
// doctor download form13 from  program table 
Route::get('Doctor/downloadForm13File/{activate_course_id}', 'ApiDoctorCoordinatorController@downloadForm13File');


//get missing file for program 
Route::get('Doctor/getMissingFileProgram/{activate_course_id}', 'ApiDoctorCoordinatorController@getMissingFileProgram');


Route::get('Doctor/testcheck/{activate_course_id}', 'ApiDoctorController@testcheck')->middleware('isCoordinator');
//notifiction for massing file

Route::get('Doctor/notfictionForMaassingFile', 'ApiDoctorCoordinatorController@notfictionForMaassingFile');


//get empty file 
Route::get('Doctor/downloadEmptyFile/{file_id}', 'ApiDoctorCoordinatorController@downloadEmptyFile');
//dispaly all empty file
Route::get('Doctor/getAllEmptyFile', 'ApiDoctorCoordinatorController@getAllEmptyFile');
// exam
Route::post('Doctor/storeExamFile/{activate_course_id}', 'ApiDoctorCoordinatorController@storeExamFile')->middleware('isCoordinator');  
Route::get('Doctor/displayExamfile/{activate_course_id}', 'ApiDoctorCoordinatorController@displayExamfile')->middleware('isCoordinator');  
Route::get('Doctor/downloadExamFile/{activate_course_id}', 'ApiDoctorCoordinatorController@downloadExamFile')->middleware('isCoordinator');  
Route::get('Doctor/deleteExamFile/{activate_course_id}', 'ApiDoctorCoordinatorController@deleteExamFile')->middleware('isCoordinator');  

//answer for exam
Route::post('Doctor/storeAnswerFile/{activate_course_id}', 'ApiDoctorCoordinatorController@storeAnswerFile')->middleware('isCoordinator');  
Route::get('Doctor/displayAnswerfile/{activate_course_id}', 'ApiDoctorCoordinatorController@displayAnswerfile')->middleware('isCoordinator');  
Route::get('Doctor/downloadAnswerFile/{activate_course_id}', 'ApiDoctorCoordinatorController@downloadAnswerFile')->middleware('isCoordinator');  
Route::get('Doctor/deleteAnswerFile/{activate_course_id}', 'ApiDoctorCoordinatorController@deleteAnswerFile')->middleware('isCoordinator');  

//form12 
Route::post('Doctor/storeForm12File/{activate_course_id}', 'ApiDoctorCoordinatorController@storeForm12File')->middleware('isCoordinator');  
Route::get('Doctor/displayForm12file/{activate_course_id}', 'ApiDoctorCoordinatorController@displayForm12file')->middleware('isCoordinator');  
Route::get('Doctor/downloadForm12File/{activate_course_id}', 'ApiDoctorCoordinatorController@downloadForm12File')->middleware('isCoordinator');  
Route::get('Doctor/deleteForm12File/{activate_course_id}', 'ApiDoctorCoordinatorController@deleteForm12File')->middleware('isCoordinator');  


//form16
Route::post('Doctor/storeForm16File/{activate_course_id}', 'ApiDoctorCoordinatorController@storeForm16File')->middleware('isCoordinator');  
Route::get('Doctor/displayForm16file/{activate_course_id}', 'ApiDoctorCoordinatorController@displayForm16file')->middleware('isCoordinator');  
Route::get('Doctor/downloadForm16File/{activate_course_id}', 'ApiDoctorCoordinatorController@downloadForm16File')->middleware('isCoordinator');  
Route::get('Doctor/deleteForm16File/{activate_course_id}', 'ApiDoctorCoordinatorController@deleteForm16File')->middleware('isCoordinator');  


//form11a
Route::post('Doctor/storeForm11aFile/{activate_course_id}', 'ApiDoctorCoordinatorController@storeForm11aFile')->middleware('isCoordinator');  
Route::get('Doctor/displayForm11afile/{activate_course_id}', 'ApiDoctorCoordinatorController@displayForm11afile')->middleware('isCoordinator');  
Route::get('Doctor/downloadForm11aFile/{activate_course_id}', 'ApiDoctorCoordinatorController@downloadForm11aFile')->middleware('isCoordinator');  
Route::get('Doctor/deleteForm11aFile/{activate_course_id}', 'ApiDoctorCoordinatorController@deleteForm11aFile')->middleware('isCoordinator');  



//ILOs
Route::post('Doctor/storeILOsFile/{activate_course_id}', 'ApiDoctorCoordinatorController@storeILOsFile')->middleware('isCoordinator');  
Route::get('Doctor/displayILOsfile/{activate_course_id}', 'ApiDoctorCoordinatorController@displayILOsfile')->middleware('isCoordinator');  
Route::get('Doctor/downloadILOsFile/{activate_course_id}', 'ApiDoctorCoordinatorController@downloadILOsFile')->middleware('isCoordinator');  
Route::get('Doctor/deleteILOsFile/{activate_course_id}', 'ApiDoctorCoordinatorController@deleteILOsFile')->middleware('isCoordinator');  

//get missing file for doctor if value is true is file missing if value false the file is complete
Route::get('Doctor/getMissingFile/{activated_courses_id}', 'ApiDoctorCoordinatorController@getMissingFile');
 

Route::resource('ApiAssistant', 'ApiAssistantController');
Route::get('ApiAssistant/total_practical/{id}', 'ApiAssistantController@get_all_total_practical');
Route::get('Assistant/all_practical_course/', 'ApiAssistantController@Show_ActivatedCourse_practical');
Route::get('Assistant/currentuser', 'ApiAssistantController@currentuser');
//get descreption about activated course 
Route::get('Assistant/getDescription/{activate_course_id}', 'ApiAssistantController@getDescription');



Route::resource('ApiDoctor', 'ApiDoctorController');
Route::get('Doctor/all_theoritical_course', 'ApiDoctorController@Show_ActivatedCourse_theoritical');
Route::get('Doctor/get_comment/{id}', 'ApiDoctorController@get_comment');
Route::get('ApiDoctor/total/{id}', 'ApiDoctorController@get_all_total');
Route::get('ApiDoctor/total_practical/{id}', 'ApiDoctorController@get_all_total_practical');
Route::get('Doctor/currentuser', 'ApiDoctorController@currentuser');
Route::get('Doctor/check_admin', 'ApiDoctorController@check_admin');
Route::get('Doctor/check_coordinator/{activated_course_id}', 'ApiDoctorController@check_coordinator');


Route::resource('ApiStudent', 'ApiStudentController');


// get Question 
Route::get('getQuestion_theoritical', 'QuestionnaireQuestion@question_theoritical');
Route::get('getQuestion_practical', 'QuestionnaireQuestion@question_pratical');
Route::get('getQuestion_comment', 'QuestionnaireQuestion@question_comment');



Route::get('Student/all_theoritical_course', 'ApiStudentController@Show_ActivatedCourse_theoritical');
Route::get('Student/all_practical_course', 'ApiStudentController@Show_ActivatedCourse_practical');
Route::get('/test/{id}', 'ApiStudentController@test');
Route::post('makeEvluation_theoritical/{id}/{doctor}', 'ApiStudentController@prosses');
Route::post('makeEvluation_practical/{id}/{assistant}', 'ApiStudentController@practicalEvlution');

//student get exam   
Route::get('Student/downloadExamFile/{activate_course_id}', 'ApiStudentController@downloadExamFile');  
// to get all activated course have the same course_code  
Route::get('Student/getAllCode/{activate_course_id}', 'ApiStudentController@getAllCode');  

//get missing file for Exam
Route::get('Student/getMissingFileExam/{activate_course_id}', 'ApiStudentController@getMissingFileExam');

//test

Route::get('homee', 'HomeController@test');