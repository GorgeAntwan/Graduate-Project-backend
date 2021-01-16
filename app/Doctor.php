<?php

namespace App;
 
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\DoctorResetPasswordNotification;
use Tymon\JWTAuth\Contracts\JWTSubject;
class Doctor extends Authenticatable implements JWTSubject 
{
    use Notifiable;
    protected $guard = 'doctor';

    protected $fillable = [
      'id', 'code','name', 'email', 'password', 'department_iddepartment'
    ];
 
    protected $hidden = [
        'password', 'remember_token',
    ];
    //public $timestamps = false;
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new DoctorResetPasswordNotification($token));
    }
    public function ActivatedCourses()
    {
        return $this->belongsToMany('App\ActivatedCourses', 'activated_courses_has_doctor', 'doctor_id', 'activated_courses_id');
    }
    public function DoctorDuties()
    {
        return $this->hasMany('App\DoctorDuties', 'doctor_id');
    }
    public function Questionnaire()
    {
        return $this->hasMany('App\Questionnaire', 'doctor_id');
    }
    public function Department()
    {
        return $this->belongsTo('App\Department');
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
