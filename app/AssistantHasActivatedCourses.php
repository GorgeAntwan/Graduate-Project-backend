<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssistantHasActivatedCourses extends Model
{
    protected  $primaryKey = 'activated_courses_id';
    public function TotalAssistant()
    {
        return $this->belongsTo('App\TotalAssistant');
    }
    
}
