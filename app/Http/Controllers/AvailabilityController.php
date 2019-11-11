<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Availability;

class AvailabilityController extends Controller
{
    public static function store(Request $request)
    {
//        $time = explode(" - ", $request->input('time'));
        $payload = auth()->payload();
        $student_idd = $payload->get('id');
//        dd($student_id);
        $request->validate([
//            'driving_instructor' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'title' => 'required'
        ]);

//        dd($student_id);
        $availability = new availability([
            'driving_instructor' => $request->get('driving_instructor', $student_idd),
            'start_time' => $request->get('start_time'),
            'end_time' => $request->get('end_time'),
            'title' => $request->get('title'),
        ]);

                
        echo 'test';
        print_r($availability->driving_instructor);
        dd();
        // error zit in de if vanaf hier gaat het stuk!!
        if(Availability::checkDuplicatieErorr($availability)){
            echo 'test';
            print_r($availability->title);
            dd();
            $availability->save();

            return response()->json($availability);
        };
//        $availability->save();

    }
}
