<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $table = 'availability'; // you may change this to your name table
//    public $timestamps = false; // set true if you are using created_at and updated_at
    protected $primaryKey = 'id';

    protected $fillable = [
        'driving_instructor', 'title', 'start_time', 'end_time'
    ];

    public static function checkDuplicatieErorr($availability){
        unset($availability['created_at']);
        unset($availability['updated_at']);
//        dd($availability);

        $count = Availability::where($availability)->count();

//        dd($count);
        if ($count > 0) {
            return false;
        }else {
            return true;
        }
    }
}

