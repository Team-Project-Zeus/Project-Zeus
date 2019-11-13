<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\appointments;

class AppointmentController extends Controller
{
    private $user_id;

    public function __construct(){
        $payload = auth()->payload();
        $this->user_id = $payload->get('id');
    }

    /**
     * Before it's saves the data, it goes through the middleware and check if the id and the user_role are equal.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $payload = auth()->payload();
        $user_role = $payload->get('user_role');

        if ($user_role === 'driving_instructor') {
            $request->validate([
                'description' => 'required',
                'status' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
            ]);

            $end = strtotime($request->end_time);
            $start = strtotime($request->start_time);
            $nextWeek = $end - $start;
            $nextWeekkk = 1800;
            $halfhours = ($nextWeek / $nextWeekkk);

            for ($i = 1; $i <= $halfhours; $i++) {
                $appointment = new appointments([
                    'driving_instructor' => $this->user_id,
                    'student' => $request->get('student'),
                    'status' => $request->get('status'),
                    'description' => $request->get('description'),
                    'start_time' => $request->get('start_time'),
                    'end_time' => $request->get('end_time')
                ]);

                $time = ((($i - 1) * 30));
                $addTime = "PT" . strval($time) . "M";
                $date = new \DateTime($appointment->start_time);
                $date->add(new \DateInterval(strval($addTime)));
                $appointment->start_time = $date->format('Y-m-d H:i:s');;
                $date->add(new \DateInterval('PT30M'));
                $appointment->end_time = $date->format('Y-m-d H:i:s');;
            }
            if ($appointment->where('id', '=', $appointment)->count() === 0) {

                $appointment->save();
                return response()->json($appointment);
            } else {
                echo 'Appointment already exists';
            }
        }else{
            echo 'You dont have the right permission';
        }
    }

   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $payload = auth()->payload();
        $id = $request->id;
        $user_role = $payload->get('user_role');
        $appointment = appointments::find($id[0]);

        if ($user_role == 'driving_instructor') {

            $request->validate([
                'student' => 'required',
                'status' => 'required',
                'description' => 'required'
            ]);

            if ($appointment['student'] == null) {
                $appointment['student'] =  $this->user_id;
                $appointment['status'] =  $request->status;

                $appointment->save();
                return response()->json($appointment);
            }
            else if ($appointment['student'] == $this->user_id) {
                $appointment['student'] =  $this->user_id;
                $appointment['status'] =  $request->status;

                $appointment->save();
                return response()->json($appointment);
            } else {
                return response()->json('wrong appointment', 403);
            }

        }else if ($user_role != 'default' || 'driving_instructor'){

            if ($appointment['student'] == null) {
                $appointment['student'] =  $this->user_id;
                $appointment['status'] =  $request->status;

                $appointment->save();
                return response()->json($appointment);
            }
            if ($appointment['student'] == $this->user_id) {
                $appointment['status'] =  $request->status;
                $appointment->save();
                return response()->json($appointment);
            } else {
                return response()->json('wrong appointment', 403);
            }
        }

    }

    public function destroy(Request $request)
    {
        $payload = auth()->payload();
        $user_role = $payload->get('user_role');
        $id = $request->id;
        $appointment = appointments::find($id[0]);

        if ($user_role == 'driving_instructor') {
            if ($appointment['student'] == $this->user_id) {
                $appointment->delete();

                return response()->json($appointment);
            }
            if ($appointment['student'] == null) {
                $appointment->delete();
                return response()->json($appointment);
            } else {
                return response()->json('wrong appointment', 403);
            }
        } else if ($user_role != 'default' || 'driving_instructor'){

            if ($appointment['student'] == null) {

                $appointment->update();
                return response()->json($appointment);
            }
            if ($appointment['student'] == $this->user_id) {
                $appointment['status'] = 'available';
                $appointment['student']  = NULL;

                $appointment->update();

                return response()->json($appointment);
            } else {
                return response()->json('wrong appointment', 403);
            }
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