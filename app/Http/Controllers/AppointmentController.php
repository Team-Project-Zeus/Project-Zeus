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
        //Checks if the user role equals is to 'driving_instructor'.
        if ( $this->user_role === 'driving_instructor') {
            $request->validate([
                'description' => 'required',
                'status' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
            ]);

            $end = strtotime($request->end_time);
            $start = strtotime($request->start_time);
            $totalMinutes= $end - $start; // calcutes the total minutes from one appointment
            $thirtyMinute= 1800; //30min (1 slot)
            $totalSlots = ($totalMinutes / $thirtyMinute);

            for ($i = 1; $i <= $totalSlots; $i++) {
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

                $appointment->save();
            }

            return response()->json($appointment);

        } else {
            echo 'You dont have the right permission';
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $id = $request->id;  //Get the id from the request

        //loop threw all the id's
        foreach ($id as $appointmentid){

            $appointment = Appointment::find($appointmentid);

            if ($this->user_role == 'driving_instructor') {
                $request->validate([
                    'student' => 'required',
                    'status' => 'required',
                    'description' => 'required'
                ]);

                if ($appointment['student'] == null) {
                    $appointment['student'] =  $this->user_id;
                    $appointment['status'] =  'reseved';

                    $appointment->save();
                }
            } else if ($this->user_role != 'default' || 'driving_instructor'){

                if ($appointment['student'] == null) {
                    $appointment['student'] =  $this->user_id;
                    $appointment['status'] =  'reseverd';

                    $appointment->save();
                }

                if ($appointment['student'] == $this->user_id) {
                    $appointment['status'] =  'reseverd';
                    $appointment->save();
                }
            }
        }
        return response()->json($appointment);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $id = $request->id;

        foreach ($id as $appointmentid){

            $appointment = Appointment::find($appointmentid); //TODO add check for student/instructor id

            if ($this->user_role === 'driving_instructor') {
                if ($appointment['student'] == $this->user_id) {
                    $appointment->delete();
                }

                if ($appointment['student'] == null) {
                    $appointment->delete();
                }
            } else if ($this->user_role != 'default' || 'driving_instructor'){

                if ($appointment['student'] == null) {
                    $appointment->update();
                }

                if ($appointment['student'] == $this->user_id) {
                    $appointment['status'] = 'available';
                    $appointment['student']  = NULL;

                    $appointment->update();
                }
            }
        }
        return response()->json($appointment);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAppointmentsStudent()
    {
        $appointments = Appointment::where('student', $this->user_id)->get();

        foreach ($appointments as $id){
            $id->user->where('id' , $id->driving_instructor)->get();
        }

        if ($appointments->where('student', $this->user_id)->count() === 0){
            echo 'student has no appointments!';
        }else {
            return response()->json($appointments );
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAppointmentsInstructor()
    {
        $appointments = Appointment::where('driving_instructor', $this->user_id)->get(); //Get all the record where the 'driving_instructor' is equal to the 'user_id'

        foreach ($appointments as $m){
//             $m->user->where('id' , $m->student)->get();
             if($m->student){
                 $m->user = User::where('id', $m->student)->get();
             }

        }

        if ($appointments->where('driving_instructor', $this->user_id)->count() == 0) {
            echo 'Driving Instructor has no appointments!';
        }else {
            return response()->json($appointments);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function todaysAppointment(){
        $date = date('Y-m-d'); // Date format is year-month-day

        if ($this->user_role === 'driving_instructor') {
            $appointments = Appointment::where('driving_instructor', $this->user_id)->where(\DB::raw("(DATE_FORMAT(start_time,'%Y-%m-%d'))"), $date)->get();
        }elseif ($this->user_role === 'student'){
            $appointments = Appointment::where('student', $this->user_id)->where(\DB::raw("(DATE_FORMAT(start_time,'%Y-%m-%d'))"), $date)->get();
        }
        
        $countAppointments = $appointments->count(); //Count how much appointments there are and convert them to a digit

        if ($countAppointments > 0){
            return response()->json($appointments);
        }else{
            return 'Vandaag heeft u geen Appoinment';
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailability(){
        $user = User::find($this->user_id);
        return response()->json($user->getAvailabilityOfInstructor());
    }
}