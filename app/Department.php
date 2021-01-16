<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected  $primaryKey = 'iddepartment';
    public function Doctor()
    {
        return $this->hasMany('App\Doctor');
    }
    public function MangerDoctor()
    {
           return $this->hasOne('App\Doctor','id', 'doctor_manager_id');
    }
    public function Assistant( )
    {
        return $this->hasMany('App\Assistant');
    }
    public function LeaderOfDuties()
    {
        return $this->hasOne('App\Doctor', 'id', 'leader_of_duties_ofQuality');
    }
}
