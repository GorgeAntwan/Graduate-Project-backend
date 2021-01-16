<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
 
class File extends Model
{	
    protected $fillable = [
        'exam_original_filename', 'exam_file_path', 'answers_original_filename', 'answers_file_path', 'deadline',
        'status','program_id','activated_courses_id'
    ];  
    public function FileMissing()
    {
        return $this->hasOne('App\FileMissing', 'file_id');
    }
    public function ILOs()
    {
        return $this->hasOne('App\ILOs', 'file_id');
    }
    public function form16()
    {
        return $this->hasOne('App\form16', 'file_id');
    }
    public function form12()
    {
        return $this->hasOne('App\form12', 'file_id');
    }
    public function form_11a()
    {
        return $this->hasOne('App\form_11a', 'file_id');
    }
   public function Program()
    {
     return $this->belongsTo('App\Program');
    }
    public function ActivatedCourses()
    {
       return $this->belongsTo('App\ActivatedCourses','id', 'activated_courses_id');
    }
}
