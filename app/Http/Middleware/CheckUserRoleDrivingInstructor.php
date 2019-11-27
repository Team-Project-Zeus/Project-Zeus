<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class CheckUserRoleDrivingInstructor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $payload = auth()->payload();
        //$user_role = the role from the user, retrieved from the token.
        $user_role = $payload->get('user_role');
        $student = User::where('id', '=', $request->student)->first();

//        dd($student);
        if ($user_role === 'driving_instructor') {
            if ($student['user_role'] === 'student' or $student['user_role'] === null) {
                return $next($request);
            }else{
                return response()->json('The student is not right.', 403);
            }
        }
        return response()->json('You dont have the right role.', 403);
    }
}
