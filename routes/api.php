<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\User;
use App\Http\Resources\User as UserResource;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'CheckUserRole'], function () {
//gets all appointments from a student with the equal id from the token
    Route::get('/api/appointments/student', 'AppointmentController@showAppointmentsStudent');
//gets all appointments from a driving-instructor with the equal id from the token
    Route::get('/api/appointments/instructor', 'AppointmentController@showAppointmentsInstructor');
//This is an api resource route for the CRUD-systeem appointment
    Route::apiResource('/api/appointments', 'AppointmentController');
});

//Route::group(['middleware' => 'auth.role:default, student, driving_instructor'], function () {
    Route::get('/api/me', function (Request $request) {
        return new UserResource($request->user());
    });
    Route::patch('/api/profile', 'Account\ProfileController@update');
    Route::patch('/api/password', 'Account\PasswordController@update');
//});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('/api/login', 'Api\LoginController@login');
    Route::post('/api/register', 'Api\RegisterController@register');
    Route::post('/api/password/email', 'Api\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/api/password/reset', 'Api\ResetPasswordController@reset');
});