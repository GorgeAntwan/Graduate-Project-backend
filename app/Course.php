<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected  $primaryKey = 'code';
    protected   $keyType = 'string';
    public function ActivatedCourses()
    {
        return $this->hasMany('App\ActivatedCourses', 'course_code', 'code');
    }
}
