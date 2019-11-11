<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\appointments;

class AppointmentController extends Controller
{
    private $student_id;

    public function __construct(){
        $payload = auth()->payload();
        $this->student_id= $payload->get('id');
    }

    /**
     * Before it's saves the data, it goes through the middleware and check if the id and the user_role are equal.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'student' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        $appointment = new appointments([
            'driving_instructor' => $request->get('driving_instructor',  $this->student_id),
            'student' => $request->get('student'),
            'start_time' => $request->get('start_time'),
            'end_time' => $request->get('end_time'),
        ]);

        $appointment->save();

        return response()->json($appointment);
        //if ($appointment->where('id' ,'=', $appointment)->count() === 0) {
        //echo 'appointment already exist!';
    }

   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $appointment_id)
    {
        $request->validate([
            'driving_instructor'=>'required',
            'start_time'=>'required',
           'end_time'=>'required'
        ]);

        $product = appointments::find($appointment_id);

        $product->driving_instructor = $request->driving_instructor;
        $product->start_time = $request->start_time;
        $product->end_time = $request->end_time;

        if ($product['student'] == $this->student_id ) {
            $product->save();
            return response()->json($product);
        }else{
            return response()->json('wrong appointment',403);
        }
    }

    public function destroy($appointment_id)
    {
        $payload = auth()->payload();
        $student_id = $payload->get('id');

        $product = appointments::find($appointment_id);

        if ($product['student'] == $student_id ) {
            $product->delete();
            return response()->json($product);
        }else {
            return response()->json('not authorized' ,403);
        }
    }

    public function showAppointmentsStudent()
    {
        //methode 1:
        //$appointments = appointments::where('student', '=', $student)->get();
        //return appointmentsResource::collection($appointments);

        //methode 2:
        $appointments = appointments::where('student', '=', $this->student_id);
        $jsondata = $appointments->get();

        if ($appointments->where('student', $this->student_id)->count() === 0){
            echo 'student has no appointments!';
        }else {
            return response()->json($jsondata);
        }
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

        if ($appointments->where('driving_instructor', $instructor_id)->count() === 0) {
            echo 'Driving Instructor has no appointments!';
        }else {
            return response()->json($jsondata);
        }
    }
}