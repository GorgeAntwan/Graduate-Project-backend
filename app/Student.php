<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\StudentResetPasswordNotification;
use Tymon\JWTAuth\Contracts\JWTSubject;
class Student extends Authenticatable implements JWTSubject
{
    use Notifiable;
    protected $guard = 'student';

    protected $fillable = [
         'code', 'name', 'email', 'password' 
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new StudentResetPasswordNotification($token));
    } 
    public function ActivatedCourses()
    {
        return $this->belongsToMany('App\ActivatedCourses', 'activated_courses_students', 'student_id',  'activated_courses_id')
            ->withPivot('date', 'attendence', 'program_id', 'done', 'done_theoritical','done_theoritical_3', 'done_practical', 'done_practical','done_theoritical_2','done_practical_2','done_practical_3');
    }
     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
