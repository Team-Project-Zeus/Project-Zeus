<?php

namespace App\Http\Controllers;

use function Composer\Autoload\includeFile;
use Illuminate\Http\Request;
use App\Appointment;
use App\User;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Builder;
use DB;


class AppointmentController extends Controller
{
    private $user_id;
    private $user_role;

    public function __construct(){
        $payload = auth()->payload();
        $this->user_id = $payload->get('id');
        $this->user_role = $payload->get('user_role');
    }

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
                $appointment = new Appointment([
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
                $appointment->start_time = $date->format('Y-m-d H:i:s');
                $date->add(new \DateInterval('PT30M'));
                $appointment->end_time = $date->format('Y-m-d H:i:s');

//                $appointmentss = Appointment::where([
//                    ['driving_instructor', '==', '1'],
//                    ['student', '==', '1'],
//                    ['start_time', '==', '1'],
//                    ['end_time', '==', '1'],
//                ])->get();
//                dd($appointmentss);

                $appointment->save();
            }
                return response()->json($appointment);
            }
        else{
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
        $appointment = Appointment::find($id[0]);

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
        $appointment = Appointment::find($id[0]);

        if ($user_role === 'driving_instructor') {
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

        //         use this to get the last 5 appointments
//        $dogs = Dogs::orderBy('id', 'desc')->take(5)->get();

//        this one works but with the first user, it doesnot take all the users.
//        $test = Appointment::find($jsondata[0]['id'])->user->where('id' , $jsondata[0]['driving_instructor'])->get();
//        "$test[0]['name']" this is how you take the data from the record

        //works fine but wit out the relationship
//        $test = Appointment::where('student' , $jsondata[0]['student'])->get();
//        $comment = App\Post::find(1)->comments()->where('title', 'foo')->first();

        //pakt alleen de eerste
//        $test = Appointment::find($jsondata[0]['id'])->user->where('id' , $jsondata[0]['driving_instructor'])->get();





        $appointments = Appointment::where('student', '=', $this->user_id);
        $jsondata = $appointments->get();

        foreach ($jsondata as $m){
            $m->user->where('id' , $m->driving_instructor)->get();
        }

        if ($appointments->where('student', $this->user_id)->count() === 0){
            echo 'student has no appointments!';
        }else {
            return response()->json(array($jsondata));
        }
    }

    public function showAppointmentsInstructor()
    {
        //methode 1:
        //$appointments = appointments::where('student', '=', $student)->get();
        //return appointmentsResource::collection($appointments);

        //methode 2

        $appointments = Appointment::where('Driving_instructor', '=', $this->user_id);
        $jsondata = $appointments->get();

        if ($appointments->where('driving_instructor', $this->user_id)->count() == 0) {
            echo 'Driving Instructor has no appointments!';
        }else {
            return response()->json($jsondata);
        }
    }

    public function todaysAppointment(){
        $date = date('Y-m-d');

        if ($this->user_role === 'driving_instructor') {
            $appointments = Appointment::where('driving_instructor', $this->user_id)->where(\DB::raw("(DATE_FORMAT(start_time,'%Y-%m-%d'))"), $date)->get();
        }elseif ($this->user_role === 'student'){
            $appointments = Appointment::where('student', $this->user_id)->where(\DB::raw("(DATE_FORMAT(start_time,'%Y-%m-%d'))"), $date)->get();
        }
        
        $jsondata = $appointments->count();

        if ($jsondata > 0){
            return response()->json($appointments);
        }else{
            return 'Vandaag heeft u geen Appoinment';
        }
    }

    public function getAvailability(){
        $appointments = Appointment::where('driving_instructor', $this->user_id)->where([['status', '!=', 'reserverd'], ['student' , NULL]])->get();

        return response()->json($appointments);
    }
}