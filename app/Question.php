<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function Questionnaire()
    {
        return $this->belongsToMany('App\Questionnaire', 'contains', 'questions_id', 'questionnaire_id')
            ->withPivot('total', 'answer');
    }
}
