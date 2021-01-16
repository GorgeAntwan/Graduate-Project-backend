<?php

    namespace App;

    use Illuminate\Notifications\Notifiable;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Foundation\Auth\User as Authenticatable;
    use App\Notifications\StudentResetPasswordNotification;

    class Total extends Authenticatable
    {
        use Notifiable;
        protected $table = 'total';
        protected $guard = 'doctor';
        protected $fillable = [
            't_1', 't_2', 't_3', 't_4', 't_5', 't_6', 't_7', 't_8', 't_9', 't_10',
             't_11','t_12', 't_13', 't_14', 't_16' ,'t_17', 't_18', 't_19' ,'t_20' ,'count', 'activated_courses_id','doctor_id'
        ];

        public function sendPasswordResetNotification($token)
        {
            $this->notify(new StudentResetPasswordNotification($token));
        }
    /*public function ActivatedCoursesHasDoctor()
        {
            return $this->hasOne('App\ActivatedCoursesHasDoctor');
        }
    */
      public function ActivatedCourses()
      {
          return $this->belongsTo('App\ActivatedCourses');
      }
    }
