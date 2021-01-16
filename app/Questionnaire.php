<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{ 


    protected $fillable = [
        'activated_courses_id' ,'comment'
    ];
    public function ActivatedCourses()
    {
        return $this->belongsTo('App\ActivatedCourses');
    }
    public function Question()
    {
        return $this->belongsToMany('App\Question', 'contains', 'questionnaire_id', 'questions_id')
        ->withPivot('total', 'answer');
    }
    public function Doctor()
    {
         return $this->belongsTo('App\Doctor');
    }
    public function Assistant()
    {
        return $this->belongsTo('App\Assistant');
    }
}
