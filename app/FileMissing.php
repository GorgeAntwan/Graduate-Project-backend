<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileMissing extends Model
{
    protected  $primaryKey = 'f_name';
    protected   $keyType = 'string';
 
    public function File()
    {
        return $this->belongsTo('App\File');
    }
}
