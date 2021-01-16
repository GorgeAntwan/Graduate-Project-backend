<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'form15_original_filename', 'form15_file_path' ,'form11b_file_path','form11b_original_filename',
        'form13_file_path' , 'form13_original_filename',
    ];
    public function File()
    {
         return $this->hasMany('App\File', 'program_id');
    }
    public function ActivatedCoursesStudents()
    {
        return $this->hasMany('App\ActivatedCoursesStudents');
    }
}
