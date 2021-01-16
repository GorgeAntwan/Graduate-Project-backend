<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmptyFiles extends Model
{
    protected $fillable = [
        
        'original_filename','file_path' 
    ];
}
