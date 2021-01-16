<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ILOs extends Model
{
    protected $table = 'ILOs';
    protected $fillable = [
        'original_filename', 'file_path', 'file_id', 
    ];
    public function File()
    {
        return $this->belongsTo('App\File');
    }
}
