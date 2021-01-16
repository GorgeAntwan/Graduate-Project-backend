<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\StudentResetPasswordNotification;

class TotalAssistants extends Authenticatable
{
    use Notifiable;
    protected $table = 'total_assistant';
    protected $guard = 'assistant';
    protected $fillable = [
       't_15','t_21', 't_22', 't_23', 't_24', 't_25','t_26', 't_27', 't_28','count', 'activated_courses_id','assistant_id'
    ];
    public function AssistantTotalUpdate($token)
    {
        $this->notify(new AssistantTotalUpdate($token));
    }
    public function  ActivatedCourses()
    {
        return $this->hasOne('App\ActivatedCourses');
    }
}
