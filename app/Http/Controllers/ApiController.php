<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\appointments;
use App\Http\Resources\appointments as appointmentsResource;



class ApiController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showStudent($student)
    {

        //methode 1:
        //$appointments = appointments::where('student', '=', $student)->get();
        //return appointmentsResource::collection($appointments);

        //methode 2
            $appointments = appointments::where('student', '=', $student);
            $jsondata = $appointments->get();


            if ($appointments->where('student', $student)->count() === 0)
                echo 'student has no appointments!';
            else
                return response()->json($jsondata);
    }

    public function showInstructor($instructor)
    {

        //methode 1:
        //$appointments = appointments::where('student', '=', $student)->get();
        //return appointmentsResource::collection($appointments);

        //methode 2
        $appointments = appointments::where('driving_instructor', '=', $instructor);
        $jsondata = $appointments->get();


        if ($appointments->where('driving_instructor', $instructor)->count() === 0)
            echo 'Driving Instructor has no appointments!';
        else
            return response()->json($jsondata);
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
}
