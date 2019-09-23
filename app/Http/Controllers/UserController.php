<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Resources\User as UserResource;

use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        Route::get('/user', function () {
            return UserResource::collection(User::all());
        });
    }
}
