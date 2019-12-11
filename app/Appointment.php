<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $primaryKey = 'id';

    public $table = "appointments";

    protected $fillable = [
        'driving_instructor', 'student', 'description','status', 'start_time', 'end_time', 'id'
    ];

    public function userInstructor()
    {
        return $this->belongsTo(User::class, 'driving_instructor', 'id');
    }

    public function userStudent()
    {
        return $this->belongsTo(User::class, 'student', 'id');
    }

    public function getAvailabilityOfInstructor(){
        $user =  User::find(auth()->payload()->get('id'));
        $appointments = Appointment::where('driving_instructor', $user->instructor_id)->where([['status', '!=', 'reserved'], ['student' , NULL]])->get();
        return $appointments;
    }

}
