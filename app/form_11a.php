<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class form_11a extends Model
{
    protected $table = 'form_11a';
    protected $fillable = [
        'original_filename', 'file_path', 'file_id', 
    ];
    public function File()
    {
        return $this->belongsTo('App\File');
    }
}
