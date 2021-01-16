<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivatedCourses extends Model
{
    public function Student()
    {
        return $this->belongsToMany('App\Student', 'activated_courses_students', 'activated_courses_id', 'student_id')
            ->withPivot('date', 'attendence', 'program_id','done', 'done_theoritical', 'done_practical','done_theoritical_2','done_theoritical_3','done_practical_2','done_practical_3');
    }
    public function Course()
    {
        return $this->belongsTo('App\Course' );
    }
    public function Questionnaire()
    {
        return $this->hasMany('App\Questionnaire', 'activated_courses_id', 'id');
    }
    public function Doctor()
    {
        return $this->belongsToMany('App\Doctor', 'activated_courses_has_doctor', 'activated_courses_id', 'doctor_id')
            ;
    }
    public function Assistant()
    {
        return $this->belongsToMany('App\Assistant', 'assistant_has_activated_courses','activated_courses_id', 'assistant_id');
    }
    public function File()
    {
       return $this->hasOne('App\File', 'activated_courses_id');
    }
    public function Total()
    {
        return $this->hasMany('App\Total','activated_courses_id', 'id');
    }
    public function TotalAssistants()
    {
        return $this->hasMany('App\TotalAssistants','activated_courses_id', 'id');
    }
}
