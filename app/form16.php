<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class form16 extends Model
{
    
    protected $table = 'form16';
    protected $fillable = [
        'original_filename', 'file_path', 'file_id', 
    ];
    public function File()
    {
        return $this->belongsTo('App\File');
    }
}
