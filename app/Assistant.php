<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use App\Notifications\AssistantResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
class Assistant extends Authenticatable implements JWTSubject
{ 
  use Notifiable;
  protected $guard = 'assistant';

  protected $fillable = [
    'code', 'name', 'email', 'password', 'department_iddepartment'
  ];

  protected $hidden = [
    'password', 'remember_token',
  ];
  public function sendPasswordResetNotification($token)
  {
    $this->notify(new AssistantResetPasswordNotification($token));
  }
    public function SkillsAssistant()
    {
         return $this->hasMany('App\SkillsAssistant');
    }
    public function ActivatedCourses()
    {
      return $this->belongsToMany('App\ActivatedCourses', 'assistant_has_activated_courses',  'assistant_id', 'activated_courses_id');
    }
    public function Questionnaire()
    {
       return $this->hasMany('App\Questionnaire','assistant_id' );
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
