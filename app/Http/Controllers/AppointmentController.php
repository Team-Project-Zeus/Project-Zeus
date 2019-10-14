<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\appointments;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'driving_instructor'=>'required',
            'start_time'=>'required',
            'end_time'=>'required'
        ]);

        $payload = auth()->payload();
        $student_id = $payload->get('id');

        $appointment = new appointments([
            'driving_instructor' => $request->get('driving_instructor'),
            'student' => $request->get('student' ,$student_id),
            'start_time' => $request->get('start_time'),
            'end_time' => $request->get('end_time'),
        ]);

//        if ($appointment->where('student', $student_id)->count() === 0)
//            echo 'student has no appointments!';
//        else

        $appointment->save();

        return response()->json($appointment);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function showAppointmentsStudent()
    {
        //methode 1:
        //$appointments = appointments::where('student', '=', $student)->get();
        //return appointmentsResource::collection($appointments);

        //methode 2:
        $payload = auth()->payload();
        $student_id = $payload->get('id');
        $appointments = appointments::where('student', '=', $student_id);
        $jsondata = $appointments->get();

        if ($appointments->where('student', $student_id)->count() === 0)
            echo 'student has no appointments!';
        else
            return response()->json($jsondata);
    }

    public function showAppointmentsInstructor()
    {
        //methode 1:
        //$appointments = appointments::where('student', '=', $student)->get();
        //return appointmentsResource::collection($appointments);

        //methode 2

        $payload = auth()->payload();
        $instructor_id = $payload->get('id');
        $appointments = appointments::where('Driving_instructor', '=', $instructor_id);
        $jsondata = $appointments->get();

        if ($appointments->where('driving_instructor', $instructor_id)->count() === 0)
            echo 'Driving Instructor has no appointments!';
        else
            return response()->json($jsondata);
    }
}