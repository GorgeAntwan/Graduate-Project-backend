<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivatedCoursesHasDoctor extends Model
{
    protected $table = 'activated_courses_has_doctor';
    protected  $primaryKey = 'activated_courses_id';
    public function Total()
    {
        return $this->belongsTo('App\Total');
    }
}
