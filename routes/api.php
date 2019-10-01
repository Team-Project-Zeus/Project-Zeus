<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::group(['middleware' => 'auth.role:Admin, Default'], function () {
    Route::get('auth/me', function (Request $request) {
        return new UserResource($request->user());
    });
    Route::patch('auth/account/profile', 'Account\ProfileController@update');
    Route::patch('auth/account/password', 'Account\PasswordController@update');
});
Route::group(['middleware' => 'guest:api'], function () {
    Route::post('api/login', 'Api\LoginController@login');
    Route::post('api/register', 'Api\RegisterController@register');
    Route::post('api/password/email', 'Api\ForgotPasswordController@sendResetLinkEmail');
    Route::post('api/password/reset', 'Api\ResetPasswordController@reset');
});

//Route::get('products', ['middleware' => 'auth.role:admin,user', 'uses' => 'ProductController@index', 'as' => 'products']);
