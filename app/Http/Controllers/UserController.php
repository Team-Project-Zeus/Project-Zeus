<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Resources\User as UserResource;

use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    public function show($id)
    {
        $userr = User::findOrFail($id);

        return new UserResource($userr);
    }
}
