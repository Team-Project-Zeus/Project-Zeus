<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class appointments extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'driving_instructor', 'student', 'description','status', 'start_time', 'end_time'
    ];
}
