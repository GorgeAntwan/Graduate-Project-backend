<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class form12 extends Model
{
    protected $table = 'form12';
    protected $fillable = [
        'original_filename', 'file_path', 'file_id', 
    ];
    public function File()
    {
        return $this->belongsTo('App\File');
    }
}
