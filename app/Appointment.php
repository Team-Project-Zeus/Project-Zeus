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

    public function user()
    {
        return $this->belongsTo(User::class, 'driving_instructor', 'id');
    }

    public function userStudent()
    {
        return $this->belongsTo(User::class, 'student', 'id');
    }
}
