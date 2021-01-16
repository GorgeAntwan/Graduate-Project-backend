<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoctorDuties extends Model
{
    protected  $primaryKey = 'id';
    protected   $keyType = 'string'; 
    protected $fillable = [
        'id', 'duties','doctor_id' 
      ];
    
    public function Doctor()
    {
       return $this->belongsTo('App\Doctor');
    }
}
