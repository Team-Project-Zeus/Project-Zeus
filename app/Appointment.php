<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $primaryKey = 'id';

    public $table = "appointments";

    protected $fillable = [
        'driving_instructor', 'student', 'description','status', 'start_time', 'end_time'
    ];

    public function test() {
        return 'test';
    }
}
