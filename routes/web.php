<?php
use App\Http\Middleware\CheckAttendence;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/Test', 'Test@creat');
Auth::routes();
Route::view('/', 'welcome');
Route::get('/users/logout', 'Auth\LoginController@userLogout')->name('user.logout');
Route::get('/home', 'HomeController@index');
Route::group(['prefix' => 'doctor'], function () {
    Route::get('/', 'DoctorController@index')->name('doctor.dashboard');
    Route::get('/login', 'Auth\DoctorLoginController@showLoginForm')->name('doctor.login');
    Route::post('/login', 'Auth\DoctorLoginController@login')->name('doctor.login.submit');
    Route::get('/logout', 'Auth\DoctorLoginController@logout')->name('doctor.logout');
    Route::get('/register', 'Auth\DoctorRegisterController@showRegistrationForm')->name('doctor.register');
    Route::post('/register', 'Auth\DoctorRegisterController@register')->name('doctor.register.submit');
    // Password reset routes
    Route::post('/password/email', 'Auth\DoctorForgotPasswordController@sendResetLinkEmail')->name('doctor.password.email');
    Route::get('/password/reset', 'Auth\DoctorForgotPasswordController@showLinkRequestForm')->name('doctor.password.request');
    Route::post('/password/reset', 'Auth\DoctorResetPasswordController@reset');
    Route::get('/password/reset/{token}', 'Auth\DoctorResetPasswordController@showResetForm')->name('doctor.password.reset');
  Route::get('/test', 'DoctorController@get_all_course');
});
Route::get('/doctor-home', 'DoctorController@home');
Route::group(['prefix' => 'assistant'], function () {
    Route::get('/', 'AssistantController@index')->name('assistant.dashboard');
    Route::get('/login', 'Auth\AssistantLoginController@showLoginForm')->name('assistant.login');
    Route::post('/login', 'Auth\AssistantLoginController@login')->name('assistant.login.submit');
    Route::get('/logout', 'Auth\AssistantLoginController@logout')->name('assistant.logout');
    Route::get('/register', 'Auth\AssistantRegisterController@showRegistrationForm')->name('assistant.register');
    Route::post('/register', 'Auth\AssistantRegisterController@register')->name('assistant.register.submit');
    // Password reset routes
    Route::post('/password/email', 'Auth\AssistantForgotPasswordController@sendResetLinkEmail')->name('assistant.password.email');
    Route::get('/password/reset', 'Auth\AssistantForgotPasswordController@showLinkRequestForm')->name('assistant.password.request');
    Route::post('/password/reset', 'Auth\AssistantResetPasswordController@reset');
    Route::get('/password/reset/{token}', 'Auth\AssistantResetPasswordController@showResetForm')->name('assistant.password.reset');
    Route::get('/test', 'AssistantController@get_all_course');
}); 
Route::get('/assistant-home', 'AssistantController@home');
Route::group(['prefix' => 'student'], function () {
    Route::get('/test', 'StudentController@get_all_information_about_student');
    Route::get('/', 'StudentController@index')->name('student.dashboard');
    Route::get('/login', 'Auth\StudentLoginController@showLoginForm')->name('student.login');
    Route::post('/login', 'Auth\StudentLoginController@login')->name('student.login.submit');
    Route::get('/logout', 'Auth\StudentLoginController@logout')->name('student.logout');
    Route::get('/register', 'Auth\StudentRegisterController@showRegistrationForm')->name('student.register');
    Route::post('/register', 'Auth\StudentRegisterController@register')->name('student.register.submit');
    // Password reset routes
    Route::post('/password/email', 'Auth\StudentForgotPasswordController@sendResetLinkEmail')->name('student.password.email');
    Route::get('/password/reset', 'Auth\StudentForgotPasswordController@showLinkRequestForm')->name('student.password.request');
    Route::post('/password/reset', 'Auth\StudentResetPasswordController@reset');
    Route::get('/password/reset/{token}', 'Auth\StudentResetPasswordController@showResetForm')->name('student.password.reset');
    Route::get('/{id}',  'StudentController@showEvlution' );
    Route::post('student/{id}',  'StudentController@prosses') ;
    Route::post('student/Practical/{id}/{Q_id}',  'StudentController@practicalEvlution')->name('student.Practical');
 
});

Route::get('/student-home', 'StudentController@home');
  


  
