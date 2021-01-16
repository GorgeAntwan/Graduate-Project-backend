<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivatedCoursesStudents extends Model
{ 
    protected  $primaryKey = 'activated_courses_id';
    public function Program()
    {
        return $this->belongsTo('App\Program');
    }
    public function Student()
    {
        return $this->belongsTo('App\Student');
    }
}
