<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//
use App\User;
use App\Http\Resources\User as UserResource;


//Route::get('api/user', function () {
//    return UserResource::collection(User::all());
//});

Route::get('/', function () {
    return view('welcome');
})->name('/');

Auth::routes();

Route::get('/error', 'HomeController@error')->name('error');

Route::middleware(['CheckRole'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
});


Route::get('/api/users', 'UserController@index');
